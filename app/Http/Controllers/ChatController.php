<?php

namespace App\Http\Controllers;

use DB;
use App\User;
use App\Room;
use App\Chat;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function chat(Request $request)
    {
    	$title = 'Matcha :: Chat';

    	$id = $request->user()->id;

    	$rooms = Room::where(function($query) use ($id){

    		$query->where('id_1', $id)
    				->orWhere('id_2', $id);

    	})->get();

    	$data = [];

    	//get target user icon and login by rooms
    	foreach ($rooms as $value){
    		$target_id = $value->id_1 != $id ? $value->id_1 : $value->id_2;

    		$query = DB::table('users')
    					->join('infos', 'infos.id', 'users.id')
    					->where('users.id', $target_id)
    					->where('infos.id', $target_id)
    					->select('users.login', 'infos.icon')
    					->first();

    		array_push($data,[
    			'icon' => $query->icon === 'spy.png' ? '/img/icons/spy.png' : '/storage/' . $query->login . '/icon/' . $query->icon,
    			'login' => ucfirst(strtolower($query->login)),
    			'room' => $value->room_id,
    			'new' => !empty(Chat::find($value->room_id)) ? 0 : 1
    		]);
    	}

    	return (view('chat')->with('data', $data)->with('title', $title));
    }

    public function getMessages(Request $request)
    {
    	$user = User::where('login', $request->data['login'])
    				->first();

    	$room = collect(Room::find($request->data['room']));

    	if (empty($user) ||
    		empty($room) ||
    		!$room->search($user->id) ||
    		!$room->search($request->user()->id))
    		return;

    	$chat = Chat::where('room_id', $room['room_id'])->get();

    	$data = [];
    	foreach ($chat as $msgs){
    		array_push($data, [
    			'user' => $msgs->user_id == $request->user()->id ? 'auth' : 'target',
    			'msg' => $msgs->msg,
                'time' => self::getMessageTime($msgs->time_sending),
    		]);
    	}

    	return (json_encode(['data' => $data]));
    }

    protected static function getMessageTime($time)
    {
        if (Carbon::now()->format('Y-m-d') ===
            Carbon::createFromFormat('Y-m-d H:i:s', $time)->format('Y-m-d')){
            return (
                Carbon::createFromFormat('Y-m-d H:i:s', $time)->format('H:i')
            );
        }
        else if (Carbon::now()->format('Y-m') ===
                Carbon::createFromFormat('Y-m-d H:i:s', $time)->format('Y-m')){
            return (
                Carbon::createFromFormat('Y-m-d H:i:s', $time)->format('m-d H:i')
            );
        }
        else
            return (
                Carbon::createFromFormat('Y-m-d H:i:s', $time)->format('Y-m-d H:i')
            );
    }
}
