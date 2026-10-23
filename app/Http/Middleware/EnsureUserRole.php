<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserRole
{
    /**
     * Usage: ->middleware('role:admin') or ->middleware('role:customer')
     */
    public function handle(Request $request, Closure $next, string $role): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        // Active check
        if (!$user->is_active) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account has been deactivated. Please contact support.');
        }

        // Role check
        $allowed = array_map('trim', explode('|', $role));
        if (!in_array($user->role, $allowed)) {
            // Redirect to their proper dashboard
            return $user->isAdmin()
                ? redirect()->route('admin.dashboard')->with('error', 'Access denied.')
                : redirect()->route('customer.dashboard')->with('error', 'Access denied.');
        }

        return $next($request);
    }
}
