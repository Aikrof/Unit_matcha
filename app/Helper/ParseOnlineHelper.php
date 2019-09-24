<?php

namespace App\Helper;

use Carbon\Carbon;

class ParseOnlineHelper
{
	public static function getUserOnline(String $online)
	{
		if (Carbon::now()->format('Y-m-d H') ===
            Carbon::createFromFormat('Y-m-d H:i:s', $online)->format('Y-m-d H')){

            $carbon_minute = Carbon::now()->format('i');
            $online_minute = Carbon::createFromFormat('Y-m-d H:i:s', $online)->format('i');
            return (
                ($carbon_minute - $online_minute > 5) ?
                'Online' : 'Last seen ' . Carbon::createFromFormat('Y-m-d H:i:s', $online)->format('H:i')
            );
        }
        else{
            return ('Last seen ' . Carbon::createFromFormat('Y-m-d H:i:s', $online)->format('Y-m-d H:i')
            );
        }
	}
}