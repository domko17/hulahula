<?php

namespace App\Providers;

use App\Models\Meeting;
use App\Models\PackageOrder;
use App\Models\StarOrder;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

        view()->composer('layouts.app', function () {
            {   //log last activity on user;
                if (Auth::user()) {
                    $logged_user = Auth::id();
                    $user = User::find($logged_user);

                    $user->last_activity = time();
                    $user->save();
                }
            }

            $notif = [];

            if (Auth::user()) {
                $logged_user = Auth::id();
                $user = User::find($logged_user);
                $profile = $user->profile;

                $new_messages = $user->getNewMessages();

                view()->share('new_messages', $new_messages);

                if ($profile->set == 0) {
                    $notif[] = ["code" => "un_profile_not_set",
                        "code_id" => "UN_1",
                        "color" => "info",
                        "icon" => "fa fa-user",
                        "link" => route('user.profile.edit', $user->id),
                        "data" => []];
                }

                if ($user->hasRole('student')) {
                    /*if (($profile->stars_individual + $profile->stars_collective) == 0) {
                        $notif[] = ["code" => "st_no_stars",
                            "code_id" => "ST_1",
                            "color" => "danger",
                            "icon" => "fa fa-star",
                            "link" => route('buy_stars.index'),
                            "data" => []];
                    } elseif (($profile->stars_individual + $profile->stars_collective) < 5) {
                        $notif[] = ["code" => "st_low_stars",
                            "code_id" => "ST_2",
                            "color" => "warning",
                            "icon" => "fa fa-star-o",
                            "link" => route('buy_stars.index'),
                            "data" => []];
                    }*/
                }

                if ($user->hasRole('teacher')) {
                    if($meeting = Meeting::teachersNearestMeeting($user->id)){
                        $notif[] = ["code" => "th_nearest_meeting",
                            "code_id" => "TH_1",
                            "color" => "primary",
                            "icon" => "mdi mdi-presentation",
                            "link" => route('teacher.nearest_meeting', $meeting->id),
                            "data" => ['meeting_data' => $meeting]];
                    }
                }

                if ($user->hasRole('admin') or $user->hasRole('developer')){
                    $has_new_orders = PackageOrder::where("paid", 0)->where('canceled', 0)->count();
                    if ($has_new_orders) $notif[] = ["code" => "ad_new_orders",
                        "code_id" => "AD_1",
                        "color" => "primary",
                        "icon" => "mdi mdi-folder-star",
                        "link" => route('admin.star-orders.index'),
                        "data" => ['count' => $has_new_orders]];
                    view()->share('has_new_orders', $has_new_orders);

                    $users_birthday = User::birthdayWeek();
                    if(count($users_birthday) > 0){
                        $notif[] = ["code" => "ad_bd_notice",
                            "code_id" => "AD_2",
                            "color" => "info",
                            "icon" => "mdi mdi-cake-variant",
                            "link" => route('admin.birthdays'),
                            "data" => ['count' => count($users_birthday)]];
                    }
                }
            }


            view()->share('notif_count', count($notif));
            view()->share('notif_arr', $notif);
        });
    }
}
