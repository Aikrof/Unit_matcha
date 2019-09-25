<?php

namespace App\Helper;

use Auth;
use App\Interests;
use Illuminate\Support\Collection;

class SortSearchHelper
{
	/*
    |--------------------------------------------------------------------------
    | Sort Search Helper
    |--------------------------------------------------------------------------
    |
    | 
    */

    /**
    * Sort search request
    *
    * @param  Illuminate\Support\Collection $query
    * @param  sort params array $params
    * @return Collection $result
    */
    public static function sortData(Collection $query, $params)
    {


    	$sort = self::validateSortParams($params);

        if (!empty($sort['tags'])){
            self::addInterestsMatch($query, $sort['tags']);
            $check = false;
        }
        else{
            $check = true;
        }

        return (
        	$sort['sorted_by'] === 'ASC' ?
        	self::sortAsc($query, $sort, $check) :
        	self::sortDesc($query, $sort, $check)
        );
    }

    public static function sortAsc(Collection $query, array $sort, int $check)
    {
        if ($check){
            return (
                $query->sortBy($sort['priority'])
            );
        }
        else{
            return (
                $query = $query->sort(function($a, $b) use ($sort) {
                    if ($a->interests_matched == $b->interests_matched)
                    {
                        if ($a->{$sort['priority']} == $b->{$sort['priority']})
                            return 0;
                        if ($a->{$sort['priority']} > $b->{$sort['priority']})
                            return 1;
                        if ($a->{$sort['priority']} < $b->{$sort['priority']})
                            return -1;
    
                    }
                    if ($a->interests_matched > $b->interests_matched)
                        return -1;
                    if ($a->interests_matched < $b->interests_matched)
                        return 1;
                })
            );
        }
    }

    public static function sortDesc(Collection $query, array $sort, int $check)
    {
        if ($check){
            return (
                $query->sortByDesc($sort['priority'])
            );
        }

    	else{
            return (
                $query = $query->sort(function($a, $b) use ($sort) {
                    if ($a->interests_matched == $b->interests_matched)
                    {
                        if ($a->{$sort['priority']} == $b->{$sort['priority']})
                            return 0;
                        if ($a->{$sort['priority']} > $b->{$sort['priority']})
                            return -1;
                        if ($a->{$sort['priority']} < $b->{$sort['priority']})
                            return 1;
    
                    }
                    if ($a->interests_matched > $b->interests_matched)
                        return -1;
                    if ($a->interests_matched < $b->interests_matched)
                        return 1;
                })
            );
        }
    }

    /**
    * Add count of interests match
    *
    * @param  Illuminate\Support\Collection $params
    * @return void
    */
    protected static function addInterestsMatch(Collection &$query, $tags)
    {
        foreach ($query as $value){
            if (!empty($value->tags))
            {
                $count = count(array_intersect($tags, $value->tags));
            }
            else
                $count = 0;

            $value->interests_matched = $count;
        }
    }

    /**
    * Add count of interests match
    *
    * @param  Illuminate\Support\Collection $params
    * @return void
    */
    protected static function setNullInterestsMatch(Collection &$query)
    {
        foreach ($query as $value){
            $value->interests_matched = 0;
        }
    }

    /**
    * Validate sort params
    *
    * @param  sort params $params
    * @return array $params
    */
    protected static function validateSortParams($params)
    {
        return ([
            'priority' => self::getPrioryty($params),
            'tags' => self::getInterests($params),
            'sorted_by' => self::getSortedBy($params)
        ]);
    }

    /**
    * Get priority to sort
    *
    * @param  sort params $params
    * @return string sort priority or null
    */
    protected static function getPrioryty($params)
    {
          if (!empty($params['priority']) &&
            empty($params['interests']) &&
            $params['priority'] === 'same_tags')
            return ('distance');

        return (empty($params['priority'])) ? 'distance' : $params['priority'];
    }

    /**
    * Get priority to sort by interests
    *
    * @param  sort params $params
    * @return array tags or null
    */
    protected static function getInterests($params)
    {
        if (!empty($params['priority']) &&
            empty($params['interests']) &&
            $params['priority'] === 'same_tags')
        {
            if (!empty($interests = Interests::find(Auth::user()->id)))
                return (explode(',', $interests->tags));
        }

        if (empty($params['interests']))
            return (null);
        
        return (explode(',', $params['interests']));
    }

    /**
    * Get priority to sort by ASC or DESC
    *
    * @param  filter params $params
    * @return string sort (ASC or DESC)
    */
    protected static function getSortedBy($params)
    {
        return (empty($params['sorted_by']) ? 'ASC' : $params['sorted_by']);
    }
}