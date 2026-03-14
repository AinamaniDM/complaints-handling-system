<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (auth()->user()->role !== $role) {
            // Redirect to the correct dashboard instead of a hard 403
            if (auth()->user()->isAdmin()) {
                return redirect()->route('admin.dashboard')
                    ->with('error', 'Admins cannot access user pages.');
            }
            return redirect()->route('user.dashboard')
                ->with('error', 'You do not have permission to access that page.');
        }

        return $next($request);
    }
}
