<?php

namespace App\Http\Middleware;

use Auth;
use Cache;
use Closure;
use App\User;
use Carbon\Carbon;

class CheckUserOnlineStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check()){
            if (!Cache::has('online' . Auth::user()->id)){

                User::find(Auth::user()->id)
                    ->update(['online' => Carbon::now()]);
                
                Cache::put(
                    'online' . Auth::user()->id,
                    true,
                    Carbon::now()->addMinutes(5)
                );
            }
        }

        return $next($request);
    }
}
