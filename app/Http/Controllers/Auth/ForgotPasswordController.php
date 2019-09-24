<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($this->credentials($request));

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );
        
        return $response == Password::RESET_LINK_SENT
                    ? json_encode(['email_send' => ['status' =>true]])
                    : json_encode(['email_send' => ['status' =>false]]);
    }

    /**
     * Validate the email for the given request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateEmail(array $email)
    {
         $custom_message = [
            'required' => 'The :attribute field is empty.',
        ];

        $validation = Validator::make($email, [
            'email' => ['required', 'string', 'email', 'max:64'],
        ], $custom_message);

        if ($validation->fails())
        {
            die(
                json_encode($validation->messages()->first())
            );
        }

        $db_email = User::where('email', $email)->first();

        if (!isset($db_email))
                die(json_encode(trans('auth.email_exist')));
        if (!User::checkEmailDomain($email['email']))
                die(json_encode(trans('auth.inv_email')));
    }
}
