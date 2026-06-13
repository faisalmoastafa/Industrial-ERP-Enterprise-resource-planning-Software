<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class RestoreController extends Controller
{
    // =========================================================================
    // START OF NEW CODE: BACKEND CONSTRUCTOR GATEKEEPER
    // =========================================================================
    /**
     * Protects the controller assets using Spatie authentication tracking rules.
     */
    public function __construct()
    {
        // Enforces standard login access AND checks for the separate access_restore permission
        $this->middleware(['auth', 'permission:access_restore']);
    }
    // =========================================================================
    // END OF NEW CODE
    // =========================================================================
    /**
     * Display the restoration utility dashboard view.
     */
    public function index()
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403);

        return view('restore.restore'); 
    }

    /**
     * Handle the complete extraction, verification, and restoration of system files.
     */
    public function systemRestore(Request $request)
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403);

        // 1. Core Input Validation
        $request->validate([
            'backup_file' => 'required|file|mimes:zip|max:512000'
        ]);

        $zipFile = $request->file('backup_file');
        $extractPath = storage_path('app/temp-restore');

        Log::channel('security')->warning('Restore started', [
            'user_id' => auth()->id(),
            'file_name' => $zipFile->getClientOriginalName(),
            'file_size' => $zipFile->getSize(),
        ]);

        $zip = new ZipArchive;
        if ($zip->open($zipFile->getRealPath()) === TRUE) {
            
            // Establish a temporary staging environment workspace folder
            if (File::exists($extractPath)) {
                File::deleteDirectory($extractPath);
            }

            if (!File::exists($extractPath)) {
                File::makeDirectory($extractPath, 0755, true);
            }

            if (!$this->extractBackupArchive($zip, $extractPath)) {
                $zip->close();
                File::deleteDirectory($extractPath);
                Log::channel('security')->warning('Restore blocked unsafe archive', [
                    'user_id' => auth()->id(),
                    'file_name' => $zipFile->getClientOriginalName(),
                ]);
                toast('Restore cancelled: backup archive contains unsafe paths.', 'error');
                return back();
            }

            $zip->close();

            $sqlPath = $extractPath . DIRECTORY_SEPARATOR . 'database.sql';
            $sqlitePath = $extractPath . DIRECTORY_SEPARATOR . 'database.sqlite';
            
            // 2. CRITICAL PRE-FLIGHT CHECK: Ensure SQL script or SQLite DB exists
            if (File::exists($sqlitePath)) {
                try {
                    $targetDbPath = config('database.connections.sqlite.database');
                    File::copy($sqlitePath, $targetDbPath);
                } catch (\Exception $e) {
                    Log::channel('security')->error('Restore database import failed', [
                        'user_id' => auth()->id(),
                        'file_name' => $zipFile->getClientOriginalName(),
                        'error' => $e->getMessage(),
                    ]);
                    toast('Database import failed: ' . $e->getMessage(), 'error');
                    return back();
                }
            } elseif (File::exists($sqlPath) && File::size($sqlPath) > 0) {
                try {
                    $sqlContent = File::get($sqlPath);
                    
                    // Drop constraint blocks so related relational logs can be refreshed cleanly
                    Schema::disableForeignKeyConstraints();
                    
                    // Safely loop through and clear current local system layout mapping
                    foreach (DB::select('SHOW TABLES') as $table) {
                        $tableArray = (array)$table;
                        $tableName = reset($tableArray);
                        Schema::drop($tableName);
                    }
                    
                    // Execute the raw SQL payload queries natively in the DB sandbox environment
                    DB::unprepared($sqlContent);
                    
                    // Re-align structural schema security constraints
                    Schema::enableForeignKeyConstraints();
                    
                } catch (\Exception $e) {
                    // Fail-safe: Always reactivate tracking rules if engine crashes midway
                    Schema::enableForeignKeyConstraints();
                    Log::channel('security')->error('Restore database import failed', [
                        'user_id' => auth()->id(),
                        'file_name' => $zipFile->getClientOriginalName(),
                        'error' => $e->getMessage(),
                    ]);
                    toast('Database import failed: ' . $e->getMessage(), 'error');
                    return back();
                }
            } else {
                // Safe Abort Execution: Wipe temporary runtime artifacts and alert the user
                File::deleteDirectory($extractPath);
                Log::channel('security')->warning('Restore blocked missing database file', [
                    'user_id' => auth()->id(),
                    'file_name' => $zipFile->getClientOriginalName(),
                ]);
                toast('Restore cancelled: backup file is empty or missing database.sql/database.sqlite!', 'warning');
                return back();
            }

            // 3. User Asset & Product Images Restoration 
            $assetsPath = $extractPath . DIRECTORY_SEPARATOR . 'system_assets';
            if (File::exists($assetsPath)) {
                // Copy directory tree layout structure straight back over to public system uploads
                File::copyDirectory($assetsPath, storage_path('app/public'));
            }

            $envBackupPath = $extractPath . DIRECTORY_SEPARATOR . '.env_backup';
            if (File::exists($envBackupPath)) {
                try {
                    File::copy($envBackupPath, base_path('.env'));
                } catch (\Exception $e) {
                    Log::channel('security')->info('Skipped .env restore due to read-only app path (expected in desktop mode)');
                }
            }

            // 4. Runtime Workspace Clean Up
            File::deleteDirectory($extractPath);
            
            // Bust Laravel compiled application view states and memory caches instantly
            Artisan::call('cache:clear');
            Artisan::call('view:clear');

            Log::channel('security')->warning('Restore completed', [
                'user_id' => auth()->id(),
                'file_name' => $zipFile->getClientOriginalName(),
            ]);

            toast('System Restored Successfully!', 'success');
            return back();
        }

        Log::channel('security')->warning('Restore failed invalid zip', [
            'user_id' => auth()->id(),
            'file_name' => $zipFile->getClientOriginalName(),
        ]);

        toast('Restore Failed: Invalid File.', 'error');
        return back();
    }

    private function extractBackupArchive(ZipArchive $zip, string $extractPath): bool
    {
        $maxFiles = 20000;
        $maxUncompressedBytes = 2 * 1024 * 1024 * 1024;
        $totalUncompressedBytes = 0;

        if ($zip->numFiles > $maxFiles) {
            return false;
        }

        for ($i = 0; $i < $zip->numFiles; $i++) {
            $entryStats = $zip->statIndex($i);
            if ($entryStats === false) {
                return false;
            }

            $totalUncompressedBytes += (int) ($entryStats['size'] ?? 0);
            if ($totalUncompressedBytes > $maxUncompressedBytes) {
                return false;
            }

            $entryName = $zip->getNameIndex($i);
            $normalizedName = str_replace('\\', '/', $entryName);

            if (
                $normalizedName === '' ||
                str_starts_with($normalizedName, '/') ||
                preg_match('/^[A-Za-z]:\//', $normalizedName) ||
                str_contains($normalizedName, '../') ||
                str_contains($normalizedName, '..\\')
            ) {
                return false;
            }

            if (str_ends_with($normalizedName, '/')) {
                continue;
            }

            $targetPath = $extractPath . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $normalizedName);
            $targetDirectory = dirname($targetPath);

            if (!File::exists($targetDirectory)) {
                File::makeDirectory($targetDirectory, 0755, true);
            }

            $source = $zip->getStream($entryName);
            if ($source === false) {
                return false;
            }

            $target = fopen($targetPath, 'wb');
            if ($target === false) {
                fclose($source);
                return false;
            }

            stream_copy_to_stream($source, $target);
            fclose($source);
            fclose($target);
        }

        return true;
    }

    public function openBackupFolder()
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403);
        $backupDir = env('BACKUP_DIR', base_path('backup'));
        if (File::exists($backupDir) && strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            exec('explorer "' . $backupDir . '"');
        }
        return response()->json(['success' => true]);
    }
}
