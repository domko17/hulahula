<?php

namespace App\Http\Controllers;

use App\Notifications\NewHulaChatMessageNotification;
use App\Notifications\PushDemo;
use App\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class PushController extends Controller
{
    /**
     * Store the PushSubscription.
     *
     * @param Request $request
     * @return JsonResponse
     * @throws ValidationException
     */
    public function store(Request $request){
        $this->validate($request,[
            'endpoint'    => 'required',
            'keys.auth'   => 'required',
            'keys.p256dh' => 'required'
        ]);
        $endpoint = $request->endpoint;
        $token = $request->keys['auth'];
        $key = $request->keys['p256dh'];
        $user = Auth::user();
        if($user)
            $user->updatePushSubscription($endpoint, $key, $token);

        return response()->json(['success' => true],200);
    }
    /**
     * Send Push Notifications to all users.
     *
     * @return Response
     */
    public function push(){
        Notification::send(User::where("id", Auth::id())->get(),new NewHulaChatMessageNotification());
        return redirect()->back();
    }
}
