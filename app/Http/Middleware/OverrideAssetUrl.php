<?php

// app/Http/Middleware/OverrideAssetUrl.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Log;

class OverrideAssetUrl
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app()->environment('local')) {
            config(['app.url' => 'http://127.0.0.1:8000']);
            // Log::info('OverrideAssetUrl middleware is setting APP_URL to local');
        }

        return $next($request);
    }
}
