<?php

namespace Modules\User\Http\Controllers;

use App\Models\User;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;

class ActivityLogController extends Controller
{
    public function index()
    {
        abort_if(Gate::denies('access_activity_log'), 403);

        $entries = $this->hydrateActorDetails($this->readSecurityLogs());

        return view('user::activity-log.index', compact('entries'));
    }

    public function show(string $entry)
    {
        abort_if(Gate::denies('access_activity_log'), 403);

        $logEntry = $this->hydrateActorDetails($this->readSecurityLogs())
            ->firstWhere('id', $entry);

        abort_if(!$logEntry, 404);

        return view('user::activity-log.show', compact('logEntry'));
    }

    private function hydrateActorDetails($entries)
    {
        $userIds = $entries
            ->pluck('context.user_id')
            ->filter()
            ->unique()
            ->values();

        $users = User::with('roles')
            ->whereIn('id', $userIds)
            ->get()
            ->keyBy('id');

        return $entries->map(function ($entry) use ($users) {
            $user = $users->get(data_get($entry, 'context.user_id'));

            $entry['actor_name'] = $user?->name ?? 'System / Unknown';
            $entry['actor_email'] = $user?->email;
            $entry['actor_roles'] = $user
                ? $user->roles->pluck('name')->implode(', ')
                : 'N/A';

            return $entry;
        });
    }

    private function readSecurityLogs()
    {
        $files = collect(File::glob(storage_path('logs/security*.log')) ?: [])
            ->sortDesc()
            ->take(7);

        return $files
            ->flatMap(fn ($file) => $this->readLogFile($file))
            ->sortByDesc('timestamp')
            ->take(300)
            ->values();
    }

    private function readLogFile(string $file)
    {
        if (!File::exists($file)) {
            return collect();
        }

        return collect(file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) ?: [])
            ->map(fn ($line) => $this->parseLogLine($line, $file))
            ->filter();
    }

    private function parseLogLine(string $line, string $file): ?array
    {
        if (!preg_match('/^\[(.*?)\]\s+\w+\.(\w+):\s+(.*)$/', $line, $matches)) {
            return null;
        }

        $message = trim($matches[3]);
        $context = [];
        $jsonStart = strpos($message, '{');

        if ($jsonStart !== false) {
            $json = substr($message, $jsonStart);
            $decoded = json_decode($json, true);
            $context = is_array($decoded) ? $decoded : [];
            $message = trim(substr($message, 0, $jsonStart));
        }

        return [
            'id' => hash('sha256', $file . '|' . $line),
            'timestamp' => $matches[1],
            'level' => strtoupper($matches[2]),
            'message' => $message,
            'context' => $context,
            'summary' => $this->summarizeContext($context),
        ];
    }

    private function summarizeContext(array $context): string
    {
        $parts = [];

        foreach (['role_name', 'new_name', 'target_email', 'source', 'reference', 'product_id', 'file_name', 'delta'] as $key) {
            if (array_key_exists($key, $context) && !is_array($context[$key])) {
                $parts[] = str_replace('_', ' ', $key) . ': ' . $context[$key];
            }
        }

        return $parts ? implode(' | ', $parts) : 'Open details';
    }
}
