<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function dashboard() {
		return view('user.dashboard');
	}

	public function upload(){
		return view('upload.index');
	}

	public function leave() {
		return view('leave.index');
	}
}
