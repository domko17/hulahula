<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = User::find(Auth::id());
        if($user->hasRole("admin")){
            return $next($request);
        }

        //TODO redirect to page with info about no access permission
        return redirect()
            ->route('dashboard')
            ->with("message", "Access denied")
            ->with("msg_type", "danger");
    }
}
