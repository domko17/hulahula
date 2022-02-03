<?php

namespace App\Http\Controllers\Helpers;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Auth;

class LocaleController extends Controller
{
    public function setLocale($locale)
    {
        session_start();
        session(['locale' => $locale]);

        $user = Auth::user();

        if ($user) {
            $profile = $user->profile;
            $profile->locale = $locale;
            $profile->save();
        }


        return redirect()->back();
    }
}
