<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\SettingRepository;
use Auth;
use Validator;

use App\Libs\Helpers;

class LoginController extends Controller
{
  public function index(Request $request)
	{
		$bool = is_bool(env('APP_FRESH')) ? env('APP_FRESH') : true;
		if($bool)
		{
			return view('Setting.initApp');
		}
		// Redirects to home if the user is already logged into the application.

		if (Auth::check()){
			return redirect('/');
		}
		
		return view('Login.login');
	}

	public function postLogin(Request $request)
	{
		// Redirects to home if the user is already logged into the application.
		if (Auth::check()){
			return redirect('/');
		}
		
		// Validates input.
		$rules = array(
			'username' => 'required|max:100',
			'password' => 'required|max:100'
		);
		$validator = Validator::make($request->all(), $rules);
		// Validation fails?
		if ($validator->fails()){
			return redirect()
				->back()
				->withErrors($validator)
				->withInput($request->except('password'));
		}
			//dd($request->all());
		if (!Auth::attempt($request->all())){
			//$request->session()->flash('errorMessages', array(trans('messages.errorInvalidLogin')));
			$request->session()->flash('errorLogin', 'Username atau Password Salah');
			return redirect()->back()
				->withInput($request->except('password'));
		};
		
		$cafeName = SettingRepository::getCafeName();

		$request->session()->put('cafeName', $cafeName);
		$request->session()->put('username', Auth::user()->getUserName());
		$request->session()->put('userid', Auth::user()->getAuthIdentifier());
		return redirect()->intended(); 
	}
		
	public function getLogoff(Request $request)
	{
		$request->session()->flush();
		Auth::logout();
		return redirect('/');
	}
}
