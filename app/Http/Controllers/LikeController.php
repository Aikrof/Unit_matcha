<?php

namespace App\Http\Controllers;

use App\Likes;
use App\Info;
use App\User;
use App\Img;
use App\Room;
use App\Chat;
use App\Helper\ProfileAddRatingHelper as ProfileRating;
use Illuminate\Http\Request;

class LikeController extends Controller
{
    public function getCountLikes(Request $request)
    {
        exit (json_encode(
            Likes::where('img', $request->img)->count()
        ));
    }

    public function getLikes(Request $request)
    {
        $likes = Likes::where('img', $request->img)->get();

        if (empty($likes[0]))
        	exit (json_encode(['empty' => 'There are no likes yet']));

        $data = [];

        foreach ($likes as $key => $value){
            $info = Info::find($value->id);
            $user = User::find($value->id);

            $icon = $info->icon === 'spy.png' ? '/img/icons/spy.png' : '/storage/' . $user->login . '/icon/' . $info->icon;

            array_push($data, [
                'icon' => $icon,
                'login' => ucfirst(strtolower($user->login)),
            ]);
        }
        
        exit (json_encode(['data' => $data]));
    }

    public function addLike(Request $request)
    {
    	$target_id = (int)base64_decode($request->like['id']);

    	if ($request->user()->id == $target_id)
    		exit;

        $info = Info::find($request->user()->id);
        $user = User::find($request->user()->id);

        if ($this->checkIfUserLike($request->like['img'], $request->user()->id))
        {
            ProfileRating::removeFromRating($target_id, 'like');
               
            Likes::where('img', $request->like['img'])->where('id', $request->user()->id)->delete();

            $remove = $this->removeConnect($request->user()->id, $target_id);

            exit(json_encode(['remove' => [
                'login' => $user->login,

                'notification' => [
                    'type' => 'newNotification',
                    'to_id' => $target_id,
                    'action' => (!$remove) ? 'dislike' : 'remove_connect',
                ],

            ]]));
        }
        else
        {
            ProfileRating::addToRating($target_id, 'like');

            Likes::create([
                'id' => $request->user()->id,
                'img' => $request->like['img']
            ]);

            $add = $this->addConnect($request->user()->id, $target_id);

            exit(json_encode(['add' => [
                'icon' => $info->icon === 'spy.png' ? '/img/icons/spy.png' : '/storage/' . $user->login . '/icon/' . $info->icon,
                'login' => $user->login,

                'notification' => [
                    'type' => 'newNotification',
                    'to_id' => $target_id,
                    'action' => (!$add) ? 'like' : 'connect',
                ],

            ]]));
        }
    }

    private function checkIfUserLike(string $img, int $user_id)
    {
        $likes = Likes::where('img', $img)->where('id', $user_id)->first();

        return (!empty($likes));
    }

    /**
    * Adds connection if auth user and target user liked imgs each other. Creates private room.
    *
    * @param  int $auth_id
    * @var int $target_id
    * @return boolean
    */
    private function addConnect(int $auth_id, int $target_id)
    {
        $img = Img::find($auth_id);
        
        if (empty($img))
            return (false);

        $img = explode(',', $img->img);
        
        $check = 0;
        foreach ($img as $value){
            if (!empty(Likes::where('img', $value)
                        ->where('id', $target_id)
                        ->first())){
                $check = 1;
                break;
            }
        }

        if (!$check)
            return (false);

        $room = Room::where(function($query) use ($auth_id, $target_id){

            $query->where('id_1', $auth_id)
                    ->orWhere('id_2', $auth_id);
        
        })->where(function($query) use ($auth_id, $target_id){
            
            $query->where('id_1', $target_id)
                    ->orWhere('id_2', $target_id);
        
        })->first();
        
        if (!empty($room))
            return (false);

        Room::create([
            'id_1' => $auth_id,
            'id_2' => $target_id
        ]);

        return (true);
    }

    /**
    * Removes connection from auth user and target user if there are no more pictures liked from auth user.
    *
    * @param  int $auth_id
    * @var int $target_id
    * @return boolean
    */
    private function removeConnect(int $auth_id, int $target_id)
    {
        //if true ---> return
        if (self::checkAnotherLikeImg(Img::find($target_id), $auth_id))
            return (false);

        $room = Room::where(function($query) use ($auth_id, $target_id){

            $query->where('id_1', $auth_id)
                    ->orWhere('id_2', $auth_id);
        
        })->where(function($query) use ($auth_id, $target_id){
            
            $query->where('id_1', $target_id)
                    ->orWhere('id_2', $target_id);
        
        })->first();

        if (empty($room))
            return (false);

        $room->delete();
        $chat = Chat::find($room->room_id);
        
        if (empty($chat))
            return (true);
        
        $chat->delete();

        return (true);
    }

    /**
    * Check if user like another img of target user
    *
    * @param  App\Img  $img
    * @var int $user_id
    * @return boolean
    */
    private static function checkAnotherLikeImg(Img $img, int $user_id)
    {
        if (empty($img))
            return (false);

        $img = explode(',', $img->img);
        
        $check = 0;
        foreach ($img as $value){
            if (!empty(Likes::where('img', $value)
                        ->where('id', $user_id)
                        ->first())){
                $check = 1;

                break;
            }
        }

        return ($check);
    }
}
