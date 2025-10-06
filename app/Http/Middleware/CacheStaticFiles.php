<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CacheStaticFiles
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        if ($request->is('img/*')) {
            if ($response->headers->has('Content-Type')) {
                $response->headers->set('Cache-Control', 'max-age=2592000, public');

                $path = public_path($request->path());
                if (file_exists($path)) {
                    $lastModified = gmdate('D, d M Y H:i:s', filemtime($path)) . ' GMT';
                    $response->headers->set('Last-Modified', $lastModified);
                }
            }
        }


        return $response;
    }
}
