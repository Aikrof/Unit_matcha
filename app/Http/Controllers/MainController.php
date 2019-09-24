<?php

namespace App\Http\Controllers;

use App\Location;
use App\Helper\FilterSearchHelper as Filter;
use App\Helper\SortSearchHelper as Sort;
use Illuminate\Http\Request;

class MainController extends SearchController
{
	/**
     * Show the index page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	if (empty($request->sort))
    		$request->sort = ['priority' => 'same_tags'];

    	$query = parent::supplementUserInfo(
    		parent::defaultSearch(
    			parent::validateSearchRequest()
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
}
