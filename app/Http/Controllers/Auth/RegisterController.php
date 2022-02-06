<?php

namespace App\Http\Controllers\Auth;

use App\Models\User\Profile;
use App\Models\UserPackage;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/thank-you';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param array $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param array $data
     * @return User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $name = explode(' ', $user->name);
        $lastName = str_replace($name[0],'', $user->name);

        $profile = new Profile();
        $profile->user_id = $user->id;
        $profile->first_name = $name[0];
        $profile->last_name = isset($lastName) ? $lastName : " ";
        $profile->phone = $data['phone'];
        $profile->save();

//        $user->attachRole('guest');

        $free_package = new UserPackage();
        $free_package->user_id = $user->id;
        $free_package->type = 99; // STARTER
        $free_package->state = 1;
        $free_package->classes_left = 1;
        $free_package->save();

        return $user;
    }
}
