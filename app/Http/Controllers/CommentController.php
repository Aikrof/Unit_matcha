<?php

namespace App\Http\Controllers;

use DB;
use App\Info;
use App\User;
use App\Comments;
use App\Helper\ProfileAddRatingHelper as ProfileRating;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function addComment(Request $request)
    {
    	if (empty($request->comment['comment']))
    		exit;

        $target_id = base64_decode($request->comment['id']);

        if ($request->user()->id != (int)$target_id)
            ProfileRating::addToRating($target_id, 'comment');

    	Comments::create([
    		'id' => $request->user()->id,
    		'img' => $request->comment['img'],
    		'comment' => $request->comment['comment']
    	]);

    	$user = Info::find($request->user()->id);

        //user data
        $data['data'] = [
            'icon' => $user->icon === 'spy.png' ? '/img/icons/spy.png' : '/storage/' . $request->user()->login . '/icon/' . $user->icon,
            'login' => ucfirst(strtolower($request->user()->login)),
            'comment' => $request->comment['comment']
        ];

        //add notification if user does not commented himself
        if ($target_id != $request->user()->id)
            $data['notification'] = [
                'type' => 'newNotification',
                'to_id' => $target_id,
                'action' => 'comment',
            ];

    	exit(json_encode($data));
    }

    public function getComments(Request $request)
    {
    	$comments = DB::select('SELECT * FROM `comments` WHERE `img` = "' . $request->commentImg . '"');
    	
    	if (empty($comments))
    		exit (json_encode(['empty' => 'There are no comments yet']));

    	$data = [];

    	foreach ($comments as $key => $value){

    		$info = Info::find($value->id);
    		$user = User::find($value->id);

    		array_push($data, [
    			'icon' => $info->icon === 'spy.png' ? '/img/icons/spy.png' : '/storage/' . $user->login . '/icon/' . $info->icon,
    			'login' => ucfirst(strtolower($user->login)),
    			'comment' => $value->comment
    		]);
    	}
    	
    	exit (json_encode(['data' => $data]));
    }
}
