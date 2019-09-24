<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Info;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/landing';

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
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
    */
    public function showRegistrationForm()
    {
        abort(404);
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $data = $request->all();
        
        event(new Registered($user = $this->createUser($data)));
        $this->createInfo($data, $user['id']);
        
        exit(json_encode(['susses_registr' => 'Please, check your email to confirm registration']));
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $custom_message = [
            'required' => 'The :attribute field is empty.',
            'gender.required' => 'Select your gender.',
            'password.regex' => 'Password must have letters
                                 numbers and special characters like: @, #, $, %, else... 
                                ',
        ];

        $validation = Validator::make($data, [
            'first_name' => ['required', 'string', 'max:20', 'alpha', 'regex:/^[A-Za-z][A-Za-z]*$/'],
            'last_name' => ['required', 'string', 'max:20', 'alpha', 'regex:/^[A-Za-z][A-Za-z]*$/'],
            'login' => ['required', 'string', 'alpha_dash', 'between:4,24', 'unique:users', 'regex:/^[A-Za-z].([A-Za-z0-9-_])+$/'],
            'email' => ['required', 'string', 'email', 'max:64', 'unique:users'],
            'password' => ['required', 'string', 'min:8', 'same:confirm', 'regex:/^(?=.*[a-z])(?=.*[0-9])(?=.*[^\w\s]).{8,}/'],
            'confirm' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[0-9])(?=.*[^\w\s]).{8,}/'],
            'gender' => ['required'],
        ], $custom_message);

        if ($validation->fails())
        {
            die(
                json_encode($validation->messages()->first())
            );
        }
        else if (!User::checkEmailDomain($data['email']))
            die(
                json_encode('Invalid email address')
            );
        return ($validation);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function createUser(array $data)
    {
        return User::create([
            'login' => strtolower($data['login']),
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
    }

    /**
     * Create a new info instance after a valid registration.
     *
     * @param  array  $data
     * @return void
     */
    protected function createInfo(array $data, int $user_id)
    {
        $icon = 'spy.png';

        Info::create([
            'id' => $user_id,
            'first_name' => ucfirst(strtolower($data['first_name'])),
            'last_name' => ucfirst(strtolower($data['last_name'])),
            'gender' => $data['gender'],
            'icon' => $icon,
        ]);
    }
}
