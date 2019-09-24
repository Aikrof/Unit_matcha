<?php

namespace App\Http\Controllers;

use Image;
use App\Info;
use App\Img;
use App\Comments;
use App\Likes;
use App\Helper\ProfileAddRatingHelper as Rating;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class ImageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Image Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling users image save request
    |
    */

    /**
    * Takes request to add new user icon.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return json image src
    */
    public function userIcon(Request $request)
    {
        $file = ($request->only('icon'))['icon'];

        $this->validateImg($file);
        
        $this->deleteOldIcon($request->user()['login']);
    	
        $file_path = $this->saveIcon($request->file('icon'), $request->user()['login'], $request->user()['id']);

        exit(json_encode(['src' => $file_path]));
    }

    /**
    * Takes request to add new user icon.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return json array with image src and user id
    */
    public function userImg(Request $request)
    {
        $file = $request->file('img');

        $this->validateImg($file);

        $file_path = $this->saveImg($file, $request->user()['login'], $request->user()['id']);

        exit(json_encode(['src' => [
            'img_src' => $file_path,
            'id' => base64_encode($request->user()->id)]
        ]));
    }

    /**
    * Takes request with image to delete this user image.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return void
    */
    public function deleteImg(Request $request)
    {
        $img = Img::find($request->user()->id);

        $user_imgs = explode(',', $img->img);
        $index = array_search($request->img, $user_imgs);
        
        if ($index === false)
            exit;

        Comments::where('img', $user_imgs[$index])->delete();
        Likes::where('img', $user_imgs[$index])->delete();
        
        $this->deleteOldImg($request->user()->login, $user_imgs[$index]);

        if (count($user_imgs) == 1)
        {
            $img->delete();
        }
        else
        {
            unset($user_imgs[$index]);
            $user_imgs = implode(',', $user_imgs);
            $img->img = $user_imgs;
            $img->save();
        }

        exit();
    }

   /**
    * Get all user images
    *
    * @param  \Illuminate\Http\Request  $request
    * @return array $data
    */
    public function getImgs(Request $request)
    {
        $user_imgs = Img::find($request->user()->id);
        
        if (empty($user_imgs))
            exit;

        $img = explode(',', $user_imgs->img);
        $data = [];

        foreach ($img as $value){
            $img_path = '/storage/' . $request->user()->login . '/' . $value;

            array_push($data, $img_path);
        }

        exit(json_encode($data));
    }

    //https://laravel.com/docs/5.8/filesystem

    /**
     * Get a validator for an incoming image save request.
     *
     * @param Symfony\Component\HttpFoundation\File\UploadedFile object with request user file $data
     * @return json answer || void
     */
    private function validateImg($data)
    {
        $file = array('image' => $data);

        $validator = Validator::make($file, [
            'image' => 'mimes:jpeg,jpg,png,gif|required|max:7000000|image',
        ]);

        if ($validator->fails())
            die(json_encode(['error' => $validator->messages()->first()]));
    }

    /**
     * Delete old user icon if it exists
     *
     * @param  String user login  $request
     * @return void
     */
    private function deleteOldIcon(String $login)
    {
        $path_to_dir = storage_path('app/public/' . $login . '/icon');
        $glob = glob($path_to_dir . '/*');
        $is_empty = count(glob($path_to_dir . '/*')) ? false : true;
        if (!$is_empty)
            unlink($glob[0]);
    }

    /**
     * Delete old image if it exists
     *
     * @param  String user login  $request
     * @param  String user img  $request
     * @return void
     */
    private function deleteOldImg(String $login, String $img)
    {
        $path_to_file = storage_path('app/public/' . $login . '/' . $img);

        if (file_exists($path_to_file))
            unlink($path_to_file);
    }

    /**
     * Save icon to storage/app/profiles/'user login'/icon
     * add new icon in to database
     *
     * @param  Illuminate\Http\UploadedFile $file
     * @param  String user login $login
     * @param  Integer user id $id
     * @return Path to new icon $path
     */
    private function saveIcon($file, $login, $id)
    {
        $created_path = $file->store('public/' . $login . '/icon');
        
        $name = explode('/', $created_path);
        $name = $name[count($name) - 1];
        
        /**
        * Intervention
        **/
        Image::make($file)->resize(124, 124)->save(storage_path('app/' . $created_path));

        $info = Info::find($id);
        
        if ($info->icon === 'spy.png')
            Rating::addToRating($id, 'icon');

        $info->icon = $name;
        $info->save();

        return ('/storage/' . $login . '/icon/' . $name);
    }

    private function saveImg($file, $login, $id)
    {
        $created_path = $file->store('public/' . $login);

        $name = explode('/', $created_path);
        $name = $name[count($name) - 1];

        /**
        * Intervention
        **/
        Image::make($file)->resize(2560, 1600)->save(storage_path('app/' . $created_path));

        $user_imgs = Img::find($id);
        
        if (empty($user_imgs))
        {
            Img::create([
                'id' => $id,
                'img' => $name
            ]);

        }
        else
        {
            $imgs = explode(',', $user_imgs->img);

            if (count($imgs) === 4)
            {
                $this->deleteOldImg($login, $imgs[count($imgs) - 1]);
                unset($imgs[count($imgs) - 1]);
            }
            
            array_unshift($imgs, $name);
            
            $user_imgs->img = implode(',', $imgs);
            $user_imgs->save();
        }

        return ('/storage/' . $login . '/' . $name);
    }
}
