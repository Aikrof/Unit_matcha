<?php

namespace App\Helper;

use App\User;
use App\Info;
use App\Birthday;
use App\Location;
use App\Interests;
class ProfileAddRatingHelper
{
	/*
    |--------------------------------------------------------------------------
    | Profile Add Rating Helper
    |--------------------------------------------------------------------------
    |
    | This class is responsible for calc and save user rating
    */

    /**
    * The constant array with rating points.
    *
    * @var array
    */
    const RATING = [
        'first_entry' => 1.5,
        'orientation' => 0,
        'age' => 0.5,
        'about' => 0.5,
        'birthday' => 0.5,
        'interests' => 0.006,
        'location' => 0.5,
        'icon' => 1,
        'comment' => 0.0015,
        'like' => 0.0015,
    ];

	/**
    * Updates user rating instance.
    *
    * @param  int $user_id
    * @param  string $name
    * @return void
    */
    public static function addToRating($user_id, $name)
    {
        if (!self::checkRating($user_id, $name))
            return;

        $user = User::find($user_id);
        $user->rating += self::RATING[$name];
        $user->save();
    }

    public static function removeFromRating($user_id, $name)
    {
        $user = User::find($user_id);
        $user->rating -= self::RATING[$name];
        $user->save();
    }

    private static function checkRating($user_id, $name)
    {
        if ($name === 'birthday')
        {
            $birthday = Birthday::find($user_id);
            return (empty($birthday) || (empty($birthday->day) && empty($birthday->month) && empty($birthday->year)));
        }
        else if ($name === 'location')
        {
            $location = Location::find($user_id);
            return (empty($location) || !$location->user_access);
        }
        else if ($name === 'interests')
            return (true);
        else
            return (empty(Info::find($user_id)->$name));
    }
}

