<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PageView;
use Illuminate\Support\Facades\Auth;

class TrackPageView
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        PageView::create([
            'url' => $request->fullUrl(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'user_id' => Auth::check() ? Auth::id() : null,
            'viewed_at' => now(),
        ]);

        return $next($request);
    }
}