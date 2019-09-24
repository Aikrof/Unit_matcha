<?php

namespace App\Http\Controllers;

use DB;
use App\Location;
use App\BlockedUser;
use App\Helper\FilterSearchHelper as Filter;
use App\Helper\SortSearchHelper as Sort;
use Illuminate\Http\Request;


class FollowController extends SearchController
{
    public function getFollowing(Request $request)
    {
    	$title =  'Matcha' . ' :: Following';

        $query = parent::supplementUserInfo(
                    self::getFollowQuery(
                        'followers_id',
                        $request->user()->id,
                        'follows.following_id'
                    ),
                Location::find($request->user()->id),
                $request->sort
            );

        $query = Sort::sortData(
                Filter::filterData($query,$request->filter),
                $request->sort
            );

        $data = $query->paginate(config('paginate.rows'));

		return (view('index', ['title' => $title, 'section' => 'following','data' => $data, 'param' => $request->all(), 'paginate' => $data]));
    }

    public function getFollowers(Request $request)
    {
        $title = 'Matcha' . ' :: Followers';

        $query = parent::supplementUserInfo(
                    self::getFollowQuery(
                        'following_id',
                        $request->user()->id,
                        'follows.followers_id'
                    ),
                Location::find($request->user()->id),
                $request->sort
            );

        $query = Sort::sortData(
                Filter::filterData($query,$request->filter),
                $request->sort
            );

        $data = $query->paginate(config('paginate.rows'));

        return (view('index', ['title' => $title, 'section' => 'followers','data' => $data, 'param' => $request->all(), 'paginate' => $data]));
    }

    protected static function getFollowQuery(String $search_id, $user_id, $take_id)
    {
        $query = DB::table('follows')
                    ->where($search_id, $user_id)
                    ->select($take_id, 'users.id', 'infos.icon', 'users.login', 'infos.age', 'users.rating', 'users.online','infos.first_name', 'infos.last_name', 'infos.about', 'interests.tags', 'locations.latitude', 'locations.longitude', 'locations.country', 'locations.city', 'locations.user_access')
                    ->join('locations', 'locations.id', '=', $take_id)
                    ->join('infos', 'infos.id', '=', $take_id)
                    ->join('users', 'users.id', '=', $take_id)
                    ->join('interests', 'interests.id', '=', $take_id)
                    ->get();


        foreach ($query as $key => $value){
            if (!empty(
                BlockedUser::where('user_id', $user_id)->where('blocked_user_id', $value->id)->first()
            ))
            {
                unset($query[$key]);
            }
        }

        return ($query);
    }
}
