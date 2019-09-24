<?php

namespace App\Http\Controllers\Profile;

use App\User;
use App\Info;
use App\Location;
use App\Interests;
use App\Birthday;
use App\Img;
use App\Http\Controllers\Profile\UserProfileController;
use App\Http\Controllers\Profile\TargetProfileController;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProfileController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Profile Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for redirecting user for his profile page or to the another user page 
    and displaying user information
    |
    */

    /**
    * Checking user 'login' and get user profile
    *
    * @param  \Illuminate\Http\Request  $request
    * @var string user $login
    * @return array $data
    */
    public function getUserProfile(Request $request, $login)
    {
    	$title = [
    		'title' => 'Matcha' . ' -> ' . $login,
    	];

    	if (ucfirst(strtolower($request->user()['login'])) === $login)
    		return ((new UserProfileController())->getProfile($request)->with($title));
    	else
    		return ((new TargetProfileController())->getProfile($request, $login)->with($title));
    }

    /**
    * Creating array with all user information
    *
    * @var target user $id
    * @var string user $login
    * @return array $data
    */
    protected function createArrInfo(int $id, string $login)
    {
        $data = [
            'user' => User::find($id),
            'info' => Info::find($id),
            'interests' => Interests::find($id),
            'location' => Location::find($id),
            'birthday' => Birthday::find($id),
            'content' => $this->getImg($id, $login),
        ];

        if (!empty($data['interests']))
            $data['interests'] = array_reverse(explode(',', $data['interests']->tags));

        if ($data['info']['icon'] !== 'spy.png')
            $data['info']['icon'] = '/storage/' . $login . '/icon/' . $data['info']['icon'];
        else
            $data['info']['icon'] = '/img/icons/spy.png';

        if (!empty($data['birthday']) && $this->checkBirthday($data['birthday']))
            $data['birthday'] = null;
        
        return ($data);
    }

    /**
    * Get all target user images
    *
    * @var target user $id
    * @var string user $login
    * @return array $data
    */
    protected function getImg(int $id, string $login)
    {
        $user_imgs = Img::find($id);
        
        if (empty($user_imgs))
            return (null);

        $img = explode(',', $user_imgs->img);
        $data = [];

        foreach ($img as $value){
            $img_path = '/storage/' . $login . '/' . $value;

            array_push($data, [
                'img' => $img_path,
                'id' => base64_encode($id)
            ]);
        }

        return ($data);
    }

    /**
    * Checks if at least one value in the table `birthday` have null
    *
    * @var string array $birthday
    * @return boolean
    */
    protected function checkBirthday($birthday)
    {
        return (
            in_array(null,
                ['day' => $birthday->day,
                'month' => $birthday->month,
                'year' => $birthday->year
            ])
        );
    }
}
