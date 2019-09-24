<?php

namespace App\Http\Controllers\Generator;

use App\User;
use App\Info;
use App\Location;
use App\Interests;
use App\Tags;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserCreatorController
{
    public function create()
    {
    	$index = 0;

    	while(++$index < 500)
    	{
    		$new_user = User::create([
    			'login'=> 'user' . $index,
    			'email' => 'user'. $index .'@email.com',
    			'email_verified_at' => Carbon::now()->format('Y-m-d H:i:s'),
    			'password' => Hash::make('dminakov@unit'),
    			'first_entry' => 1,
    			'rating' => rand(0, 100),
    			'online' => rand(1, 40) % 2 == 1 ? Carbon::now()->format('Y-m-d H:i:s') :  Carbon::yesterday()->format('Y-m-d H:i:s'),
    		]);
    		
    		$gender_rand = rand(1, 2);
    		
    		Info::create([
    			'id' => $new_user->id,
    			'first_name' => 'First Name',
    			'last_name' => 'Last Name',
    			'gender' => $gender_rand === 1 ? 'Male' : 'Female',
    			'icon' => 'spy.png',
    			'orientation' => self::getRandOrientation(),
    			'age' => rand(12, 56),
    			'about' => self::generateRandomAbout(),
    		]);

    		Location::create([
    			'id' => $new_user->id,
    			'latitude' => 50.4032446,
				'longitude' => 30.5649563,
				'country' => 'Ukraine',
				'city' => 'Kyiv',
				'user_access' => 1,
    		]);

    		$interests = self::getRandomInterests();

    		Interests::create([
    			'id' => $new_user->id,
    			'tags' => $interests,
    		]);

    		$exp = explode(',', $interests);
    		foreach ($exp as $value){
    			$tag = Tags::firstOrNew(['tag' => $value]);
                $tag->count += 1;
                $tag->users_id .= $new_user->id  . ',';
                $tag->save();
    		}
    	}
    }

    protected static function getRandOrientation()
    {
    	$rand = rand(1, 3);

    	if ($rand === 1)
    		return ('Heterosexual');
    	else if ($rand === 2)
    		return ('Bisexual');
    	else if ($rand === 3)
    		return ('Homosexual');
    }

    protected static function generateRandomAbout()
    {
    	return ('bb');
    }

    protected static function getRandomInterests()
    {
    	$random_interest = [
    		'UNIT' , '2019', 'aaaa', 'bbbbb', 'C', 'JS', 'VueJS', 'PHP', 'Mysql', 'asdd', '123', '321', 'ahaha', 'test', 'my_interests', 'football', 'project', 'matcha', 'printf', 'filler', 'fillit', 'get_next_line', 'camagru', 'hypertube', 
    	];

    	$i = rand(2,12);
    	$arr = [];
    	while (--$i)
    	{
    		$rand = rand(0, 23);
    		array_push($arr, $random_interest[$rand]);
    	}

    	return (implode(',', $arr));
    }
}
