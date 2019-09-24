<?php

namespace App\Helper;

use DB;
use App\Info;
use Illuminate\Support\Facades\Validator;

class ProfileInfoHelper
{
	/*
    |--------------------------------------------------------------------------
    | Info Helper
    |--------------------------------------------------------------------------
    |
    | This class is responsible for validate user data
    */

    public static function validateRequest($data, $user_id)
    {
        $key = key($data);
 
        $validation = Validator::make($data, [
            'first_name' => ['string', 'max:20', 'alpha'],
            'last_name' => ['string', 'max:20', 'alpha'],
            'age' => ['between:10,60', 'numeric'],
        ]);

        if ($validation->fails())
            return (null);

        if ($key === 'age' && !self::validateAge($data['age'], $user_id))
            return (null);
        else if ($key === 'birthday' && !self::validateBirthday($data, $user_id))
            return (null);
        else if ($key === 'orientation')
            return (self::validateOrientation($data));
        else if ($key === 'interests' && !self::validateInterests($data['interests'], $user_id))
            return (null);
        else if ($key === 'location' && !self::validateLocation($data))
            return (null);

        return ($data[$key]);
    }

    protected static function validateAge($age, $user_id)
    {
        $year = self::selectData('year', 'birthdays', $user_id);

        if (empty($year[0]) || empty($year[0]->year))
            return (true);
        else
            $year = $year[0]->year;

        return ((int)$year + (int)$age === (int)date('Y'));
    }

    protected static function validateBirthday($data, $user_id)
    {
        if (!self::validateMonth($data['birthday']))
            return (false);

        $birthday['birthday'] = implode('.', $data['birthday']);
        
        $validate = Validator::make($birthday, [
            'birthday' => ['date'],
        ]);

        if ($validate->fails())
            return (false);

        $day = (int)date('Y') - (int)$data['birthday']['year'];
        if ($day <= 10 || $day >= 60)
            return (false);

        $age = self::selectData('age', 'infos', $user_id)[0]->age;

        if (!empty($age) && $age !== 0)
        {
            return ((int)$data['birthday']['year'] + (int)$age === (int)date('Y'));
        }

        return (true);
    }

    protected static function validateMonth($data)
    {
        $month = [
            'January', 'February', 'March', 'April', 'May', 'June',
            'July', 'August', 'September', 'October', 'November', 'December',
        ];

        if (!in_array($data['month'], $month))
            return (false);

        return (true);
    }

    protected static function validateOrientation($data)
    {
        $orientChoice = [
            "Heterosexual", "Bisexual", "Homosexual"
        ];

        if (empty(array_intersect($data, $orientChoice)))
            return ('Bisexual');
        
        return ($data['orientation']);
    }

    protected static function validateInterests(&$interests, $user_id)
    {
        if (empty($interests))
            return (false);
        
        $interests = array_unique($interests);
        $select = self::selectData('tags', 'interests', $user_id);

        if (!empty($select))
        {
            $tags = explode(',', $select[0]->tags);
            foreach ($interests as $key => $value){
                if (in_array(strtoupper($value), $tags) ||
                    in_array(strtolower($value), $tags))
                    unset($interests[$key]);
            }

            return ($interests);
        }
        
        return (true);
    }

    protected static function validateLocation($data)
    {
        return (!in_array(null, $data['location']));
    } 

    private static function selectData($field, $table, $user_id)
    {
        return (
            DB::select('SELECT `' . $field . '` FROM `' . $table . '` WHERE `id` = ' . $user_id)
        );
    }
}