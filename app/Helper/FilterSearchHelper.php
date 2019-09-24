<?php

namespace App\Helper;

use Illuminate\Support\Collection;

class FilterSearchHelper
{
	/*
    |--------------------------------------------------------------------------
    | Filter Search Helper
    |--------------------------------------------------------------------------
    |
    | 
    */

    /**
    * Filter search request
    *
    * @param  Illuminate\Support\Collection $query
    * @param  filter params array $params
    * @return Collection $result
    */
	public static function filterData(Collection $query, $params)
	{
		if (empty($params))
			return ($query);

		$filter = self::validateFilterParams($params);

		$query = $query
				->whereBetween('age', [$filter['age']['min'], $filter['age']['max']])
				->whereBetween('rating', [$filter['rating'], '100'])
                ->where('distance', '<=', $filter['distance']);

        if (!empty($filter['tags']))
        {
            foreach ($query as $key => $value){
                if (empty($value->tags) ||
                    !array_intersect($filter['tags'], $value->tags))
                {
                    unset($query[$key]);
                }
            }
        }

        return ($query);
    }

    /**
    * Validate filter params
    *
    * @param  filter params array $params
    * @return Illuminate\Support\Collection $params
    */
    protected static function validateFilterParams(array $params)
    {
    	return ([
    			'age' => self::getAge($params),
            	'distance' => self::getDistance($params),
            	'rating' => self::getRating($params),
            	'tags' => self::getInterests($params),
    		]);
    }


    /**
    * Get age
    *
    * @param  filter params array $params
    * @return array [min age, max age]
    */
    protected static function getAge(array $params)
    {
    	if (empty($params['age']))
            $age = null;
        else
            $age = explode('-', $params['age']);

        if (empty($age) || ((!is_numeric($age[0]) ||
            !is_numeric($age[1])) ||
            (int)$age[0] > (int)$age[1]) ||
            ((int)$age[0] < 10 || (int)$age[1] > 60))
            return (['min' => '0', 'max' => '60']);
        else
            return (['min' => $age[0], 'max' => $age[1]]);
    }

    /**
    * Get distance
    *
    * @param  filter params array $params
    * @return array [min distance, max distance]
    */
    protected static function getDistance(array $params)
    {
    	if (empty($params['distance']) ||
            !is_numeric($params['distance']) ||
            (int)$params['distance'] <= 0 || 
            (int)$params['distance'] >= 100)
            return (40076);
        else
            return ((int)$params['distance']);
    }

    /**
    * Get rating
    *
    * @param  filter params array $params
    * @return rating value
    */
    protected static function getRating(array $params)
    {
    	if (empty($params['rating']) ||
            (!is_numeric($params['rating']) ||
                $params['rating'] < 0 ||
                $params['rating'] > 100))
            return ('0');
        else
            return ($params['rating']);
    }

    /**
    * Get interests
    *
    * @param  filter params array $params
    * @return rating value
    */
    protected static function getInterests(array $params)
    {
    	if (empty($params['interests']))
    		return (null);

    	return (explode(',', $params['interests']));
    }
}