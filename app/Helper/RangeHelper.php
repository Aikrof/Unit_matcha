<?php

namespace App\Helper;

use Location\Line;
use Location\Coordinate;
use Location\Distance\Haversine;

class RangeHelper
{
	public function getDistance($user_latitude, $user_longitude, $auth_latitude, $auth_longitude)
	{
		$distance = new Line(
			new Coordinate($user_latitude, $user_longitude),
			new Coordinate($auth_latitude, $auth_longitude)
		);
         
        $distance = (int)$distance->getLength(new Haversine());

        return ($distance);
	}
}