<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;

use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
	public function login(Request $request)
	{
		return view('auth.login');
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

	public function login_validate(Request $request): RedirectResponse
	{
        $credentials = $request->validate(
		[
            'email' => ['required'],
            'password' => ['required'],
        ]);

		if (Auth::attempt($credentials)) 
		{
			$request->session()->regenerate();

			return redirect()->intended('/dashboard');
		}

		return back()->withErrors([
			'email' => 'The provided credentials do not match our records.',
			'password' => 'The password credentials do not match our records.',
		]);
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

	public function asd()
	{
		$password = Hash::make('cybernet1906');

		echo $password;
	}
}

?>