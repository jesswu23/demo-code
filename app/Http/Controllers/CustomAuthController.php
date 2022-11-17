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

	private string $guard;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
        $this->guard = 'web';
    }

	public function index()
	{
		if(Auth::guard($this->guard)->user()) {
			return redirect('dashboard');
		}

		return view('auth.login');
	}

	public function customLogin(Request $request, CustomLoginRequest $customLoginRequest)
	{
		$credentials = $customLoginRequest->only('email', 'password');
		if (Auth::guard($this->guard)->attempt($credentials)) {
			$request->session()->regenerate();

			return redirect()->intended('dashboard')->withSuccess( 'logged in' );
		}

		return redirect("login")->withError( 'Email or password invalid' );
	}

	public function registration()
	{
		return view('auth.registration');
	}

	public function customRegistration(CustomRegistrationRequest $CustomRegistrationRequest)
	{
		$validated = $CustomRegistrationRequest->validated();
		$result = $this->userService->create($validated);

		return redirect("login")->withSuccess( 'Registration success, please login' );
	}

	public function logOut(Request $request)
	{
		Auth::guard($this->guard)->logout();

		$request->session()->invalidate();

		$request->session()->regenerateToken();

		return Redirect('login')->withSuccess( 'logged out' );
	}
}