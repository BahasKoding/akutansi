<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FinanceManagerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!Auth::user()->hasFinanceAccess()) {
            return response()->view('errors.unauthorized', [
                'message' => 'You need finance manager privileges to access this area.'
            ], 403);
        }

        return $next($request);
    }
}
