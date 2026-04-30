<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreventScriptExecution
{
    public function handle(Request $request, Closure $next): Response
    {
        $path = $request->getPathInfo();

        $dangerousExtensions = [
            '.php', '.phtml', '.php3', '.php4', '.php5', '.php7', '.php8',
            '.phar', '.inc', '.hijack',
        ];

        foreach ($dangerousExtensions as $ext) {
            if (str_ends_with($path, $ext)) {
                abort(403, 'File type not allowed.');
            }
        }

        return $next($request);
    }
}
