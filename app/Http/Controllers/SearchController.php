<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use App\Info;
use App\Location;
use App\Tags;
use App\Interests;
use App\BlockedUser;
use App\Helper\RangeHelper;
use App\Helper\FilterSearchHelper as Filter;
use App\Helper\SortSearchHelper as Sort;
use App\Helper\ParseOnlineHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = self::supplementUserInfo(
                    self::getSearchQuery(
                    	self::validateSearchRequest($request->search)
                    ),
                	Location::find($request->user()->id),
                	$request->sort
            );

        $query = Sort::sortData(
                Filter::filterData($query, $request->filter),
                $request->sort
            );

        $data = $query->paginate(config('paginate.rows'));
        
		return (view('index', ['section' => 'home','data' => $data, 'param' => $request->all(), 'paginate' => $data]));
    }


    protected static function supplementUserInfo(Collection $query, $user_location, $sort)
    {
        $range = new RangeHelper();

        foreach ($query as $value){
            $value->icon = $value->icon === 'spy.png' ? '/img/icons/spy.png' : '/storage/' . $value->login . '/icon/' . $value->icon;
            $value->login = ucfirst(strtolower($value->login));
            $value->tags = empty($value->tags) ? null : explode(',', $value->tags);

            if (empty($sort['sorted_by']) || $sort['sorted_by'] !== 'DESC')
            {
                $value->age = $value->age == 0 ? 999 : $value->age;
            }
            
            $value->rating = (string)$value->rating;

            $value->online = ParseOnlineHelper::getUserOnline($value->online);
            
            if (!empty($user_location))
            {
                $value->distance = (string)($range->getDistance(
                    $value->latitude,
                    $value->longitude,
                    $user_location->latitude,
                    $user_location->longitude
                ) / 1000);
            }
        }
        
        return ($query);
    }

    protected static function validateSearchRequest(array $search = null)
    {
    	$info = Info::find(Auth::user()->id);

    	if ($info->orientation === 'Homosexual')
    		$gender = $info->gender;
    	else if ($info->orientation === 'Bisexual')
    		$gender = $info->gender;
    	else
    		$gender = $info->gender === 'Male' ? 'Female' : 'Male';

    	$query = [
    		'gender' => $gender,
    		'orientation' => $info->orientation,
    		'country' => empty($search['country']) ? null : $search['country'],
    		'city' => empty($search['city']) ? null : $search['city'],
    	];

    	$priority = self::getSearchPriority($search);
    	if (!empty($priority))
    		$query = array_merge($priority, $query);

    	return ($query);
    }

    private static function getSearchPriority(array $search = null)
    {
    	if (!empty($search['login']))
    		return (['login' => $search['login']]);
    	else if (!empty($search['tags']))
    		return (['tags' => $search['tags']]);
    	else if (!empty($search['same_tags']))
    	{
    		$interests = interests::find(Auth::user()->id);
    		return (['tags' => empty($interests) ? "," : $interests->tags]);
    	}
    	else
    		return (null);
    }

    protected static function getSearchQuery(array $search)
    {

    	if (!empty($search['login']))
    		return (self::searchByLogin($search));
    	else if (!empty($search['tags']))
    		return (self::searchByTags($search));
    	else if (!empty($search['country']))
    		return (self::searchByLocation($search));
    	else
    		return (self::defaultSearch($search));
    }

    protected static function searchByLogin(array $search)
    {
    	if (strtolower($search['login']) === strtolower(Auth::user()->login))
    		return (self::defaultSearch($search));

    	return (
    		DB::table('users')
    			->where('users.login', $search['login'])
    			->join('locations', 'locations.id', '=', 'users.id')
                ->join('infos', 'infos.id', '=',  'users.id')
                ->join('interests', 'interests.id', '=',  'users.id')
    			->select('users.id', 'users.online','infos.icon', 'users.login', 'infos.age', 'users.rating', 'infos.first_name', 'infos.last_name', 'infos.about', 'interests.tags', 'locations.latitude', 'locations.longitude', 'locations.country', 'locations.city', 'locations.user_access')
    			->get()
    	);
    }

    protected static function searchByTags(array $search)
    {
    	$tags = array_filter(array_unique(explode(',', $search['tags'])));

    	$collection = collect();
    	foreach ($tags as $tag){
    		$table_tags = Tags::find($tag);
    		
    		if (empty($table_tags))
    			continue;

    		$users_id = array_filter(explode(',', $table_tags->users_id));
    		$tmp_collect = collect();
    		foreach ($users_id as $id){
                if ($id == Auth::user()->id)
                    continue;
                
    			$query = self::getUserById($id);

    			$query = self::parseQuery($query, $search);

    			if ($query->count() > 0)
    				$tmp_collect = $tmp_collect->merge($query);
    		}

    		if ($tmp_collect->count() > 0)
    			$collection = $collection->merge($tmp_collect);
    	}

    	return ($collection);
    }

    protected static function searchByLocation(array $search)
    {
    	$search_key = empty($search['city']) ? 'country' : 'city';
    	$search_val = empty($search['city']) ? $search['country'] : $search['city'];

    	$query = DB::table('locations')
    		->where('locations.' . $search_key, $search_val)
            ->where('users.id', '!=', Auth::user()->id)
            ->join('infos', 'infos.id', '=',  'locations.id')
            ->join('interests', 'interests.id', '=',  'locations.id')
            ->join('users', 'users.id', '=', 'locations.id')
    		->select('users.id', 'users.online','infos.icon', 'users.login', 'infos.age', 'users.rating', 'infos.first_name', 'infos.last_name', 'infos.about', 'interests.tags', 'locations.latitude', 'locations.longitude', 'locations.country', 'locations.city', 'locations.user_access', 'infos.gender', 'infos.orientation')
    		->get();

    	return (self::parseQuery($query, $search));
    }

    protected static function defaultSearch(array $search)
    {
    	$query = DB::table('infos')
            ->where(function($query) use ($search){
                if ($search['orientation'] === 'Homosexual')
                {
                    $query->where('infos.id', '!=', Auth::user()->id)
                        ->where('infos.orientation', 'Homosexual')
                        ->where('infos.gender', $search['gender'])
                        ->orWhere('infos.orientation', 'Bisexual')
                        ->where('infos.gender', $search['gender']);
                }
                else if ($search['orientation'] === 'Heterosexual')
                {
                    $query->where('infos.orientation', 'Heterosexual')
                        ->where('infos.gender', $search['gender']);
                }
                else
                {
                    $query->where('infos.orientation', 'Homosexual')
                        ->where('infos.gender', $search['gender'])
                        ->orWhere('infos.orientation', 'Bisexual')
                        ->where('infos.id', '!=', Auth::user()->id);
                }
            })
    		->join('locations', 'locations.id', '=', 'infos.id')
            ->join('users', 'users.id', '=',  'infos.id')
            ->join('interests', 'interests.id', '=',  'infos.id')
    		->select('users.id', 'users.online','infos.icon', 'users.login', 'infos.age', 'users.rating', 'infos.first_name', 'infos.last_name', 'infos.about', 'interests.tags', 'locations.latitude', 'locations.longitude', 'locations.country', 'locations.city', 'locations.user_access', 'infos.gender', 'infos.orientation')
    		->get();

    	return (self::parseQuery($query, $search));
    }

    protected static function getUserById(int $id)
    {
    	return (
    		DB::table('users')
    			->where('users.id', $id)
    			->join('locations', 'locations.id', '=', 'users.id')
                ->join('infos', 'infos.id', '=',  'users.id')
                ->join('interests', 'interests.id', '=',  'users.id')
    			->select('users.id', 'users.online','infos.icon', 'users.login', 'infos.age', 'users.rating', 'infos.first_name', 'infos.last_name', 'infos.about', 'interests.tags', 'locations.latitude', 'locations.longitude', 'locations.country', 'locations.city', 'locations.user_access', 'infos.gender', 'infos.orientation')
    			->get()
    	);
    }

    private static function parseQuery(Collection $query, array $search)
    {
    	foreach ($query as $key => $value){
    		if (!self::filterQuery($value, $search))
    			unset($query[$key]);
    	}

    	return ($query);
    }

    private static function filterQuery($value, array $search)
    {
        if (!empty(BlockedUser::where('user_id', Auth::user()->id)->where('blocked_user_id', $value->id)->first()))
            return (false);

    	return (true);
    }
}
