<?php

namespace App\Http\Controllers;

use Auth;
use App\User;
use App\Info;
use App\BlockedUser;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class BlockController extends Controller
{
    public function blockUser(Request $request)
    {
    	$this->validateUserLogin($request->user);

    	$block_user = User::where('login', $request->user['login'])->first();

    	if (empty($block_user))
    	{
    		die(
    			json_encode(['err' => 'Invalid user login'])
    		);
    	}

    	$block = BlockedUser::firstOrNew([
    		'user_id' => Auth::user()->id,
    		'blocked_user_id' => $block_user->id
    	]);
    	$block->save();

        $info = Info::find($block_user->id);

    	return (
    		json_encode(['user' => 
    			[
    				'icon' => $info->icon === 'spy.png' ? '/img/icons/spy.png' : '/storage/' . $block_user->login . '/icon/' . $info->icon,
    				'login' => ucfirst(strtolower($block_user->login))
    			]
    	]));
    }

    public function removeUserFromBLock(Request $request)
    {

        $this->validateUserLogin($request->user);

        $user = User::where('login', $request->user['login'])->first();

        if (empty($user))
        {
            die(
                json_encode(['err' => 'Invalid user login'])
            );
        }

        $block_user = BlockedUser::where('blocked_user_id', $user->id)->where('user_id', $request->user()->id)->first();
        
        if (empty($block_user))
        {
            die(
                json_encode(['err' => 'Invalid user login'])
            );
        }

        $block_user->delete();

        return (json_encode(['user' => ['login' => $user->login]]));
    }

    protected function validateUserLogin(array $user)
    {
    	$validation = Validator::make($user, [
            'login' => ['required', 'string', 'alpha_dash', 'between:4,24'],
        ]);

        if ($validation->fails() || strtolower($user['login']) === strtolower(Auth::user()->login))
        {
            die(
                json_encode(['err' => 'Invalid user login'])
            );
        }
    }
}
