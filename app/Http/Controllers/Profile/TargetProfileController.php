<?php

namespace App\Http\Controllers\Profile;

use App\User;
use App\Follow;
use App\BlockedUser;
use Illuminate\Http\Request;
use App\Http\Controllers\Profile\ProfileController;

class TargetProfileController extends ProfileController
{
	/*
    |--------------------------------------------------------------------------
    | Target Profile Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling target user page and act this user
    |
    */

    /**
    * Show the target user profile page.
    *
    * @param  \Illuminate\Http\Request  $request
    * @var string user $login
    * @return \Illuminate\Http\Response
    */
    public function getProfile(Request $request, String $login)
    {
    	$select = User::select('id')->where('login', $login)->first();

    	if (empty($select))
    		abort(404);
        else if (!empty(
            BlockedUser::where('user_id', $select->id)->where('blocked_user_id', $request->user()->id)->first()
        ))
        {
            return(view('target_profile')->with('data', null));
        }

    	$id = $select->id;

        Follow::firstOrCreate([
            'followers_id' => $request->user()->id,
            'following_id' => $id,
        ]);
        
    	$data = $this->createArrInfo($id, $login);
    	return (view('target_profile')->with('data', $data));
    }
}
