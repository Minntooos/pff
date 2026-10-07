<?php

namespace App\Http\Middleware;

use App\Models\Resource;
use Closure;
use Illuminate\Http\Request;

class PublicResourceMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $resource = $request->route('resource');

        // Proceed if there's no 'resource' parameter
        if (! $resource) {
            return $next($request);
        }

        // Ensure $resource is an instance of Resource
        if ($resource instanceof Resource) {
            if ($resource->is_public || auth()->check()) {
                return $next($request);
            }

            return redirect()->route('login');
        }

        // If 'resource' exists but is not a Resource instance, proceed or handle accordingly
        return $next($request);
    }
}
