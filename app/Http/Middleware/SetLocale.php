<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SetLocale
{
    public function __construct(Application $app, Request $request)
    {
        $this->app = $app;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = Auth::user();
        if ($user) {
            $profile = $user->profile;
            app()->setLocale($profile->locale);
        } else
            app()->setLocale(session('locale', config('app.locale')));

        return $next($request);
    }
}
