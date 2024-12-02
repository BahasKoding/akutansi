<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManagerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        if (!Auth::user()->isManager() && !Auth::user()->isAdmin()) {
            return response()->view('errors.unauthorized', [
                'message' => 'You need manager privileges to access this area.'
            ], 403);
        }

        return $next($request);
    }
}
