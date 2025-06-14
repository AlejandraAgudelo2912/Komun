<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RedirectIfAuthenticated
{
    public function handle(Request $request, Closure $next, string ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();

                if ($user->hasRole('admin')) {
                    return redirect()->route('admin.dashboard');
                } elseif ($user->hasRole('god')) {
                    return redirect()->route('god.dashboard');
                } elseif ($user->hasRole('verificator')) {
                    return redirect()->route('verificator.dashboard');
                } elseif ($user->hasRole('assistant')) {
                    return redirect()->route('assistant.dashboard');
                } elseif ($user->hasRole('needHelp')) {
                    return redirect()->route('needhelp.dashboard');
                }

                return redirect(RouteServiceProvider::HOME);
            }
        }

        return $next($request);
    }
}
