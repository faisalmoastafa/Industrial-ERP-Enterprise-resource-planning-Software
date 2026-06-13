<?php

namespace App\Services;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

class SessionCacheManager
{
    public function destroyCurrentSession(Request $request): void
    {
        Auth::guard()->logout();

        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
    }

    public function hardenResponse(Request $request, SymfonyResponse $response): SymfonyResponse
    {
        if ($response instanceof BinaryFileResponse) {
            return $response;
        }

        if ($this->shouldPreventBrowserCache($request, $response)) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0, private');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
            $response->headers->set('Vary', trim($response->headers->get('Vary').', Cookie', ', '));
        }

        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        $response->headers->set('Permissions-Policy', 'camera=(), microphone=(), geolocation=()');

        return $response;
    }

    public function hardenLogoutResponse(RedirectResponse $response): RedirectResponse
    {
        $response->headers->set('Clear-Site-Data', '"cache", "cookies", "storage", "executionContexts"');
        $response->headers->clearCookie(config('session.cookie'), config('session.path', '/'), config('session.domain'));
        $response->headers->clearCookie('XSRF-TOKEN', config('session.path', '/'), config('session.domain'));

        return $response;
    }

    public function sweepExpiredFileSessions(): int
    {
        if (config('session.driver') !== 'file') {
            return 0;
        }

        $path = config('session.files');

        if (! is_string($path) || ! File::isDirectory($path)) {
            return 0;
        }

        $expiresAt = now()->subMinutes((int) config('session.lifetime', 120))->getTimestamp();
        $deleted = 0;

        foreach (File::files($path) as $file) {
            if ($file->getFilename() === '.gitignore') {
                continue;
            }

            try {
                if (! $file->isFile() || $file->getMTime() >= $expiresAt) {
                    continue;
                }

                File::delete($file->getPathname());
                $deleted++;
            } catch (Throwable) {
                report('Unable to inspect or delete expired session file: '.$file->getPathname());
            }
        }

        return $deleted;
    }

    private function shouldPreventBrowserCache(Request $request, SymfonyResponse $response): bool
    {
        if ($request->isMethod('HEAD')) {
            return false;
        }

        if ($response->isRedirection()) {
            return true;
        }

        $contentType = (string) $response->headers->get('Content-Type');

        return str_contains($contentType, 'text/html')
            || str_contains($contentType, 'application/json')
            || $response instanceof Response;
    }
}
