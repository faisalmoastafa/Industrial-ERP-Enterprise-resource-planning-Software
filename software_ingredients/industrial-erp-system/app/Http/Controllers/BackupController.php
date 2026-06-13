<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use ZipArchive;

class BackupController extends Controller
{
    // =========================================================================
    // START OF NEW CODE: BACKEND CONSTRUCTOR GATEKEEPER
    // =========================================================================
    /**
     * Protects the controller assets using Spatie authentication tracking rules.
     */
    public function __construct()
    {
        // Enforces standard login access AND checks for the separate access_backup permission
        $this->middleware(['auth', 'permission:access_backup']);
    }
    // =========================================================================
    // END OF NEW CODE
    // =========================================================================
    public function index()
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403);

        $backupDir = env('BACKUP_DIR', base_path('backup'));
        $latestBackup = null;

        if (File::exists($backupDir)) {
            $files = collect(File::files($backupDir))
                ->filter(fn ($file) => strtolower($file->getExtension()) === 'zip')
                ->sortByDesc(fn ($file) => $file->getMTime())
                ->values();

            $latestBackup = $files->first();
        }

        return view('backup.index', compact('backupDir', 'latestBackup'));
    }

    public function store()
    {
        abort_unless(auth()->user()->hasRole('Super Admin'), 403);

        $backupDir = env('BACKUP_DIR', base_path('backup')); 
        
        if (!File::exists($backupDir)) {
            File::makeDirectory($backupDir, 0755, true);
        }

        // Auto-delete previous backup files to save disk space
        $oldFiles = File::files($backupDir);
        foreach ($oldFiles as $file) {
            File::delete($file);
        }

        $timestamp = now()->format('Y-m-d_His');
        $zipName = "NECI_System_Backup_{$timestamp}.zip";
        $zipPath = $backupDir . DIRECTORY_SEPARATOR . $zipName;
        
        $tempDir = storage_path('app/backup-temp');
        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        // --- SMART DATABASE EXPORT ENGINE ---
        $sqlFile = $tempDir . '/database_dump.sql';
        
        if (config('database.default') !== 'sqlite') {
            // 1. Scan Laragon's directory to find whatever MySQL folder version exists dynamically
            $mysqlBaseDir = 'C:\laragon\bin\mysql\\';
            $mysqldump = 'mysqldump'; // Fallback to global path command if available

            if (File::exists($mysqlBaseDir)) {
                $folders = File::directories($mysqlBaseDir);
                foreach ($folders as $folder) {
                    $possiblePath = $folder . DIRECTORY_SEPARATOR . 'bin' . DIRECTORY_SEPARATOR . 'mysqldump.exe';
                    if (File::exists($possiblePath)) {
                        $mysqldump = $possiblePath;
                        break; // Found a valid mysqldump.exe executable path!
                    }
                }
            }

            // 2. Build and execute the backup command string
            $cmd = sprintf('"%s" --user=%s --password=%s %s > %s',
                $mysqldump,
                escapeshellarg(config('database.connections.mysql.username')),
                escapeshellarg(config('database.connections.mysql.password')),
                escapeshellarg(config('database.connections.mysql.database')),
                escapeshellarg($sqlFile)
            );
            exec($cmd);
        }

        // --- ZIPPING PROCESS ---
        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === TRUE) {
            
            if (config('database.default') === 'sqlite') {
                $sqliteDbPath = config('database.connections.sqlite.database');
                if (File::exists($sqliteDbPath)) {
                    $zip->addFile($sqliteDbPath, 'database.sqlite');
                }
            } else {
                // Confirm the database dump file exists and has data before wrapping it in the ZIP
                if (File::exists($sqlFile) && File::size($sqlFile) > 0) { 
                    $zip->addFile($sqlFile, 'database.sql'); 
                }
            }

            if (File::exists(base_path('.env'))) {
                $zip->addFile(base_path('.env'), '.env_backup');
            }
            
            $storagePath = storage_path('app/public'); 
            if (File::exists($storagePath)) {
                $files = File::allFiles($storagePath);
                foreach ($files as $file) {
                    $zip->addFile($file->getRealPath(), 'system_assets/' . $file->getRelativePathname());
                }
            }
            $zip->close();
        }

        // Clean up temporary database dump file
        if (File::exists($sqlFile)) { 
            File::delete($sqlFile); 
        }

        // --- FINAL TOAST NOTIFICATION CHECK ---
        if (File::exists($zipPath)) {
            // Confirm if the backup zip contains the vital database file
            $verifyZip = new ZipArchive;
            if ($verifyZip->open($zipPath) === TRUE) {
                $hasDatabase = $verifyZip->locateName('database.sql') !== false || $verifyZip->locateName('database.sqlite') !== false;
                $verifyZip->close();

                if ($hasDatabase) {
                    Log::channel('security')->info('Backup created', [
                        'user_id' => auth()->id(),
                        'file' => $zipName,
                        'path' => $zipPath,
                        'has_database' => true,
                    ]);

                    toast('Backup Created Successfully with Data!', 'success');
                    return back();
                }
            }
        }

        Log::channel('security')->warning('Backup failed', [
            'user_id' => auth()->id(),
            'file' => $zipName,
            'path' => $zipPath,
        ]);

        toast('Backup Engine Error: Database file could not be generated.', 'error');
        return back();
    }
}
