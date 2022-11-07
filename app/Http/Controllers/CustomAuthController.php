<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;

use App\Services\UserService;
use App\Http\Requests\CustomLoginRequest;
use App\Http\Requests\CustomRegistrationRequest;

class CustomAuthController extends Controller
{
	protected $userService;

    public function __construct( UserService $userService ) {
        $this->userService = $userService;
    }

	public function index() {
		return view('auth.login');
	}

	public function customLogin(CustomLoginRequest $request) {
		$credentials = $request->only('email', 'password');
		if (Auth::attempt($credentials)) {
			return redirect()->intended('dashboard')->withSuccess( __('user.logged_in') );
		}

		return redirect("login")->withError( __('user.error_account_or_password') );
	}

	public function registration() {
		return view('auth.registration');
	}

	public function customRegistration(CustomRegistrationRequest $request) {
		$result = $this->userService->create($request->all());
		if($result->id) {
			return redirect("login")->withSuccess( __('user.registration_success') );
		} else {
			return redirect("registration")->withError( __('user.error_insert_failure') );
		}
	}

	public function dashboard() {
		if(Auth::check()) {
			return view('auth.dashboard');
		}

		return redirect("login")->withError( __('user.error_access_not_allowed') );
	}

	public function logOut() {
		Session::flush();
		Auth::logout();

		return Redirect('login')->withSuccess( __('user.logged_out') );
	}
}