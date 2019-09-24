<?php

namespace App\Http\Controllers\Profile;

use Auth;
use App\Info;
use App\Location;
use App\Interests;
use App\Birthday;
use App\Tags;
use App\Helper\ProfileInfoHelper as ProfileHelper;
use App\Helper\ProfileAddRatingHelper as ProfileRating;
use Illuminate\Http\Request;
use App\Http\Controllers\Profile\ProfileController;
class UserProfileController extends ProfileController
{
	/*
    |--------------------------------------------------------------------------
    | User Profile Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling user page and act this user
    |
    */

    /**
    * Show the user profile page.
    *
    * @return \Illuminate\Http\Response
    */
    public function getProfile(Request $request)
    {
    	$data = $this->createArrInfo(Auth::user()['id'], Auth::user()['login']);

    	return (view('user_profile')->with('data', $data));
    }

    public function updateProfile(Request $request)
    {
        $key = key($request->all());
        $user_id = $request->user()->id;

        $data[$key] = ProfileHelper::validateRequest($request->all(), $user_id);

        if (empty($data[$key]))
            exit;

        if ($key === 'birthday')
            exit($this->updateBirthday($data, $user_id));
        else if ($key === 'location')
            exit($this->updateLocation($data, $user_id));
        else if ($key === 'interests')
            exit($this->updateInterests($data, $user_id));
        else
            exit($this->updateInfo($data, $key, $user_id));

        $user->save();
    }

    public function removeBirthday(Request $request)
    {
        $birthday = Birthday::find($request->user()->id);

        if (!empty($birthday->day) && !empty($birthday->month) && !empty($birthday->year))
             ProfileRating::removeFromRating($request->user()->id, 'birthday');

        $birthday->day = null;
        $birthday->month = null;
        $birthday->year = null;

        $birthday->save();
    }

    public function removeTag(Request $request)
    {
        $interests = Interests::find($request->user()->id);

        if (empty($interests->tags))
            exit;

        ProfileRating::removeFromRating($request->user()->id, 'interests');

        $search = Tags::where('tag', $request->tag)->first();
        $search->count--;
        
        $users_id = explode(',', $search->users_id);
        $key = array_search($request->user()->id, $users_id);
        unset($users_id[$key]);
        $search->users_id = implode(',', $users_id);
        

        if (!$search->count)
            $search->delete();
        else
            $search->save();

        $tags = explode(',', $interests->tags);
        $remove_element = array_search($request->tag, $tags);
        unset($tags[$remove_element]);
        $interests->tags = implode(',', $tags);
        
        $interests->save();
        exit;
    }

    public function removeLocation(Request $request)
    {

        $location = Location::find($request->user()->id);

        if (!$location->user_access)
            exit;

        ProfileRating::removeFromRating($request->user()->id, 'location');

        $location->user_access = 0;
        $location->save();
        exit;
    }


    protected function updateBirthday(array $data, int $user_id)
    {
        if (empty($data['birthday']))
            exit;
        
        ProfileRating::addToRating($user_id, 'birthday');

        $birthday = Birthday::firstOrNew(['id' => $user_id]);


        foreach ($data['birthday'] as $key => $value){
            $birthday->$key = $value;
        }

        $birthday->save();
    }

    protected function updateLocation(array $data, int $user_id)
    {
        ProfileRating::addToRating($user_id, 'location');

        $location = Location::firstOrNew(['id' => $user_id]);

        foreach ($data['location'] as $key => $value){
            $location->$key = $value;
        }
        $location->user_access = 1;
        
        $location->save();
    }

    protected function updateInterests(array $data, int $user_id)
    {
        $newTag = $data['interests'][0];

        ProfileRating::addToRating($user_id, 'interests');

        $interests = Interests::firstOrNew(['id' => $user_id]);
        $tag = Tags::firstOrNew(['tag' => $newTag]);
        
        $interests_tags = explode(',', $interests->tags);
        
        if (empty($interests_tags[0]))
            unset($interests_tags[0]);

        array_push($interests_tags, $newTag);
        
        $interests->tags = implode(',', $interests_tags);
        
        $interests->save();

        $tag->count++;
        $tag->users_id .= $user_id . ',';
        $tag->save();

    }

    protected function updateInfo(array $data, string $key, int $user_id)
    {
        if (($key === 'about' || $key === 'age') && empty($data[$key]))
            ProfileRating::removeFromRating($user_id, $key);
        else
            ProfileRating::addToRating($user_id, $key);

        if ($key === 'age' && empty($data[$key]))
            $data[$key] = 0;

        $info = Info::find($user_id);

        $info->$key = $data[$key];

        $info->save();
    }
}
