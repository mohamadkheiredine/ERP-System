<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\UserSession;

class EnforceSingleSession
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {

            $session = UserSession::where('user_id', Auth::id())->first();

            if ($session && $session->session_id !== Session::getId()) {

                Auth::logout();

                return redirect('/login')->withErrors([
                    'session' => 'Your account was logged in from another device.'
                ]);
            }
        }

        return $next($request);
    }
}
