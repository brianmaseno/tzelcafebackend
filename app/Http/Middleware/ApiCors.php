<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiCors
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $origin = $request->headers->get('Origin');
        $configured = (string) env('CORS_ALLOWED_ORIGINS', 'http://localhost:5173,http://127.0.0.1:5173');
        $allowedOrigins = array_filter(array_map('trim', explode(',', $configured)));
        $allowAll = in_array('*', $allowedOrigins, true)
            || filter_var(env('CORS_ALLOW_ALL', false), FILTER_VALIDATE_BOOL);

        if ($allowAll && $origin) {
            $allowOrigin = $origin;
        } elseif ($allowAll) {
            $allowOrigin = '*';
        } else {
            $allowOrigin = in_array((string) $origin, $allowedOrigins, true) ? (string) $origin : '';
        }

        if ($request->getMethod() === 'OPTIONS') {
            $response = response('', 204);
        } else {
            $response = $next($request);
        }

        if ($allowOrigin !== '') {
            $response->headers->set('Access-Control-Allow-Origin', $allowOrigin);
            $response->headers->set('Vary', 'Origin');
            if ($allowOrigin !== '*') {
                $response->headers->set('Access-Control-Allow-Credentials', 'true');
            }
            $response->headers->set('Access-Control-Allow-Methods', 'GET,POST,PUT,PATCH,DELETE,OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Authorization,Content-Type,Accept,X-Requested-With');
        }

        return $response;
    }
}
