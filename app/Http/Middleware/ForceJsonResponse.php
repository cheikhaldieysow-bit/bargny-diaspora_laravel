<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ForceJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Force JSON parsing for API routes
        if ($request->is('api/*')) {
            $request->headers->set('Accept', 'application/json');

            // Si le corps de la requête contient du JSON mais n'a pas le bon Content-Type
            if ($request->getContent() && !$request->isJson()) {
                $content = $request->getContent();

                // Tenter de parser le JSON
                $data = json_decode($content, true);

                if (json_last_error() === JSON_ERROR_NONE && is_array($data)) {
                    // Remplacer les données de la requête avec le JSON parsé
                    $request->merge($data);
                }
            }
        }

        return $next($request);
    }
}
