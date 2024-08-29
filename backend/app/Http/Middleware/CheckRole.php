<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $role = null): Response
    {
        if(!Auth::Check()) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        if($role && !$request->user()->hasRole($role)) {
            return response()->json(['error' => 'Forbidden'], 403);        
        }

        return $next($request);
    }
}
