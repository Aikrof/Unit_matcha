<?php

namespace App\Http\Controllers;

use DB;
use Illuminate\Http\Request;

class TagsHelperController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Tags Helper Controller
    |--------------------------------------------------------------------------
    |
    | This controller search similar tags and return to user
    |
    */

    /**
    * Return similar tags
    *
    * @param  \Illuminate\Http\Request  $request
    * @return json array $similar
    */
    public function tagWriter(Request $request)
    {
        if (empty($request->tag))
            exit;
        
    	exit(json_encode(['similar' => $this->searchSimilarTags($request->tag)]));
    }

    /**
    * Search for similar tags in table
    *
    * @param  String $tags
    * @return stdClass
    */
    private function searchSimilarTags($tags)
    {
		return (
			DB::select('SELECT `tag` FROM `tags` WHERE `tag` LIKE "' . $tags . '%" LIMIT 10')
		);
    }
}
