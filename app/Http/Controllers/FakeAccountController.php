<?php

namespace App\Http\Controllers;

use App\User;
use App\FakeAccount;
use Illuminate\Http\Request;

class FakeAccountController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Fake Account Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for users reports
    |
    */

    /**
    * Report user hase fake account
    *
    * @param  \Illuminate\Http\Request  $request
    * @return json string err || success
    */
    public function fakeAccount(Request $request)
    {
    	$target_user = User::where('login', $request->user['login'])->first();

    	if (empty($target_user))
    		return (json_encode(['err' => 'User is not exist']));

    	FakeAccount::firstOrCreate([
    		'report_user_id' => $request->user()->id,
    		'fake_user_id' => $target_user->id,
    	]);

    	return (json_encode([
    		'success' => 'User was reported as a fake account'
    	]));
    }
}
