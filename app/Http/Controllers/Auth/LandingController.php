<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LandingController extends Controller
{	
	public function __construct()
    {
        $this->middleware('guest');
    }

    public function landing()
    {
        return (view('auth/landing'));
    }
}
