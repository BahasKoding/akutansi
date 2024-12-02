<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login first.');
        }

        if (!Auth::user()->isAdmin()) {
            return response()->view('errors.unauthorized', [
                'message' => 'You need administrator privileges to access this area.'
            ], 403);
        }

        return $next($request);
    }
}
