<?php

namespace App\Http\Controllers\Web\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\AuthRequest;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
	public function login(Request $request)
	{
		return view('auth.login', ['testing' => 'GOBLOK']);
	}

	public function login2(Request $request)
	{
		$username = 'administrator';

		$accounts = DB::table('accounts')->where('username', $request->input('username'))->get();

		if (count($accounts))
		{
			return view('auth.login', ['accounts' => $accounts]);
		}
		else
		{
			echo 'User tidak ditemukan';
		}

		// return view('Auth.login');

		// var_dump($accounts);
		// print_r($accounts);
		// exit;
	}

	public function authenticate(AuthRequest $request)
	{
		// $request->validate();

        // $credentials = $request->validate(
		// [
		// 	'email' => ['required'],
		// 	'password' => ['required'],
		// ]);

		if (Auth::attempt($request->validated())) 
		{
			$request->session()->regenerate();

			// return redirect()->intended('/dashboard');

			return response()->json(['status' => 'success', 'message' => Auth::attempt($request->validated()), 'redirect_url' => url('dashboard')]);
		}

		// return back()->withErrors([
		// 	'email' => 'The provided credentials do not match our records.',
		// 	'password' => 'The password credentials do not match our records.',
		// ]);

		// dd($request);

		return response()->json(['status' => 'failed', 'message' => 'The email address or password you entered is incorrect, please try again']);
	}

	// public function signup()
	// {

	// }

	// public function signup_store(Request $request)
	// {
	// 	$validate = $request->validate(
	// 	[
	// 		'name' 				=> 'required',
	// 		'email' 			=> 'required|unique:users,email',
	// 		'password'			=> 'required',
	// 		'confirm-password' 	=> 'required|same:password'
	// 	]);

	// 	$data = $request->except('confirm-password', 'password');
	// 	$data['password'] = Hash::make($request->password);

	// 	User::create($data);

	// 	return redirect('/login');
	// }

	public function logout(Request $request): RedirectResponse
	{
		Auth::logout();
	 
		$request->session()->invalidate();
	 
		$request->session()->regenerateToken();
	 
		return redirect('/auth');
	}

	public function logoutSSO(Request $request) 
	{
		Auth::guard()->logout();

		$request->session()->flush();
		$azureLogoutUrl = Socialite::driver('azure')->getLogoutUrl(route('login'));
		return redirect($azureLogoutUrl);
	}

	public function asd()
	{
		$password = Hash::make('cybernet1906');

		echo $password;
	}
}

?>