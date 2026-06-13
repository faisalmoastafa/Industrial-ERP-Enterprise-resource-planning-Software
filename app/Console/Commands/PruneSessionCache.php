<?php

namespace App\Console\Commands;

use App\Services\SessionCacheManager;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class PruneSessionCache extends Command
{
    protected $signature = 'security:prune-session-cache {--clear-cache : Clear the Laravel application cache store too}';

    protected $description = 'Remove expired file sessions and optionally clear application cache.';

    public function handle(SessionCacheManager $sessions): int
    {
        $deletedSessions = $sessions->sweepExpiredFileSessions();

        $this->info("Expired session files deleted: {$deletedSessions}");

        if ($this->option('clear-cache')) {
            Artisan::call('cache:clear');
            $this->info(trim(Artisan::output()) ?: 'Application cache cleared.');
        }

        return self::SUCCESS;
    }
}
