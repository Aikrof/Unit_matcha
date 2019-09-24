<?php

namespace App\Http\Controllers;

use DB;
use App\Info;
use App\Location;
use App\Interests;
use App\Tags;
use App\Birthday;
use App\Helper\ProfileInfoHelper as ProfileHelper;
use App\Helper\ProfileAddRatingHelper as Rating;
use Illuminate\Http\Request;

class FirstEntryController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | First Entry Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the first entry info about user
    | like user age, birthday, sexual orientation, information of user interests, and location.
    |
    */

    /**
    * Handle a user data request for the application.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return void
    */
    public function firstEntry(Request $request)
    {
        $data[key($request->all())] = ProfileHelper::validateRequest($request->all(), $request->user()->id);

        $this->userAddInfo($request, $data);
        $this->createBirthday($request, $data);
        $this->addInterests($request, $data);
        $this->createLocation($request, $data);

        exit;
    }

    /**
    * Remember the first user entrance add.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return void
    */
    public function SuccessfulUserFirstEntry(Request $request)
    {
        if (!$request->done)
            $this->setDefaultValues($request);
        else
            Rating::addToRating($request->user()['id'], 'first_entry');
        
        DB::update('update `users` set first_entry = false WHERE `id` = ?', [$request->user()['id']]);
    }

    /**
    * Set default values to table Locations, Interests
    * if not exist 
    *
    * @param  \Illuminate\Http\Request  $request
    * @return void
    */
    protected function setDefaultValues(Request $request)
    {
        if (!Location::find($request->user()->id))
            $this->createLocation($request, ['location' => null]);

        if (!Interests::find($request->user()->id))
            $this->addInterests($request, ['interests' => null]);
    }

    /**
    * Add data to info table.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param   array $ifno 
    * @return void
    */
    protected function userAddInfo(Request $request, array $info)
    {
        $key = key($info);

        if ($key !== 'orientation' &&
            $key !== 'age' &&
            $key !== 'about')
                return;

        $user_info = Info::find($request->user()['id']);

        if ($key === 'age' && empty($info[$key]))
            $info[$key] = 0;

        $user_info->update([
            $key => $info[$key]
        ]);
        $user_info->save();

        if (!empty($info[$key]))
            Rating::addToRating($request->user()['id'], $key);
    }

    /**
    * Add birthday data to birthday table.
    *
    * @param  array  $birthday
    * @return void
    */
    protected function createBirthday(Request $request, $info)
    {
        if (key($info) !== 'birthday')
            return;

        $birthday = $info['birthday'];

        Birthday::create([
            'id' => $request->user()['id'],
            'day' => $birthday['day'],
            'month' => $birthday['month'],
            'year' => $birthday['year'],
        ]);

        if (!empty($info['birthday']))
            Rating::addToRating($request->user()['id'], 'birthday');
    }

    /**
    * Add user interests in to interests table.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  array $info
    * @return void
    */
    protected function addInterests(Request $request, $info)
    {
        if ((key($info) !== 'interests'))
            return;

        if (!empty($info['interests']))
            $tags = implode(',', $info['interests']);
        else
            $tags = null;

        Interests::create([
                'id' => $request->user()['id'],
                'tags' => $tags
        ]);

        if (!empty($info['interests']))
        {
            foreach ($info['interests'] as $value){
                $tag = Tags::firstOrNew(['tag' => $value]);
                $tag->count += 1;
                $tag->users_id .= $request->user()['id']  . ',';
                $tag->save();
            }
        }

        if (!empty($info['interests']))
            for ($i = 0; $i < count($info['interests']); $i++){
                Rating::addToRating($request->user()['id'], 'interests');
            }
    }

    /**
    * Create a new location instance.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  array $info
    * @return void
    */
    protected function createLocation(Request $request, array $info)
    {
        if (key($info) !== 'location')
            return;

        $user_access = true;

        if (empty($info['location']))
        {
            $info['location'] = $this->getUserLocation();
            $user_access = false;
        }

        Location::create([
            'id' => $request->user()['id'],
            'latitude' => $info['location']['latitude'],
            'longitude' => $info['location']['longitude'],
            'country' => $info['location']['country'],
            'city' => $info['location']['city'],
            'user_access' => $user_access,
        ]);

        if (!empty($info['location']) && $user_access)
            Rating::addToRating($request->user()['id'], 'location');
    }

    /**
    * Get user location if he did not indicate.
    *
    * @return array location cords 
    */
    private function getUserLocation()
    {
        $query = @unserialize (file_get_contents('http://ip-api.com/php/'));

        return ([
            'latitude' => $query['lat'],
            'longitude' => $query['lon'],
            'country' => $query['country'],
            'city' => $query['city'],
        ]);
    }
}
