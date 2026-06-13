<?php

namespace App\Http\Middleware;

use App\Services\SessionCacheManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ManageBrowserSessionAndCache
{
    public function __construct(private readonly SessionCacheManager $sessions)
    {
    }

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $this->sessions->hardenResponse($request, $response);

        if ($this->shouldSweepSessionFiles()) {
            $this->sessions->sweepExpiredFileSessions();
        }

        return $response;
    }

    private function shouldSweepSessionFiles(): bool
    {
        $lottery = config('session.lottery', [2, 100]);
        $wins = max(0, (int) ($lottery[0] ?? 2));
        $outOf = max(1, (int) ($lottery[1] ?? 100));

        return $wins >= $outOf || random_int(1, $outOf) <= $wins;
    }
}
