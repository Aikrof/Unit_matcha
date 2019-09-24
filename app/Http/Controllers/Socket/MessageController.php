<?php

namespace App\Http\Controllers\Socket;

use Auth;
use App\User;
use App\Room;
use App\Chat;
use Carbon\Carbon;
use App\DeferredNotification as Notifi;
use App\Http\Controllers\Controller;

class MessageController extends Controller
{
	private $room  = [];

	private $msg;

    public function newMessageAdd()
    {
    	$time = Carbon::now();

    	Chat::create([
    		'room_id' => $this->room['room_id'],
    		'user_id' => $this->room['from_id'],
    		'msg' => $this->msg,
    		'time_sending' => $time
    	]);

    	self::refreshUserOnlineStatus($this->room['from_id']);

    	return ([
    		'room' => $this->room['room_id'],
    		'from' => ucfirst(strtolower(User::find($this->room['from_id'])->login)),
    		'to' => $this->room['to_id'],
    		'msg' => $this->msg,
    		'time' => Carbon::createFromFormat('Y-m-d H:i:s', $time)->format('H:i')
    	]);
    }

    public function deferNotifi()
    {
    	Notifi::create([
    		'to_id' => $this->room['to_id'],
    		'from_id' => $this->room['from_id'],
    		'type' => 'message'
    	]);
    }

    public function validateMessageRules($msg, $from_id)
    {
    	if (empty($msg->room) ||
    		empty($msg->to) ||
    		empty($msg->msg))
    	{
    		var_dump('Empty msg');
    		return (false);
    	}

    	$to_user = User::where('login', $msg->to)->first();
    	if (empty($to_user) || empty(User::find($from_id))){
    		echo "Unknown user login: " . $msg->to;
    		return (false);
    	}

    	$this->room['room_id'] = $msg->room;
    	$this->room['from_id'] = $from_id;
    	$this->room['to_id'] = $to_user->id;
    	$this->msg = $msg->msg;

    	if (!self::checkRoomAccess($this->room)){
    		echo "Room". $msg->room ."does not exist";
    		return (false);
    	}

    	return (true);
    }

    protected static function checkRoomAccess(array $room)
    {
    	$access = Room::where(function($query) use ($room){
    		$query->where('room_id', $room['room_id'])
    				->where('id_1', $room['from_id'])
    				->orWhere('id_2', $room['from_id'])
    				->where('id_1', $room['to_id'])
    				->orWhere('id_2', $room['to_id']);
    	})->first();

    	return (empty($access) ? false : true);
    }

    protected static function refreshUserOnlineStatus($id)
    {
    	User::find($id)
    		->update(['online' => Carbon::now()]);
    }
}
