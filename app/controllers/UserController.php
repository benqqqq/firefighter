<?php

class UserController extends BaseController {

	
	public function showLogin() {
		return View::make('user.login');
	}
	
	public function doLogin() {
		$rules = [
            'serial' => 'required|exists:users',
            'password' => 'required'
        ];

        $input = Input::only('serial', 'password');

		$messages = [
			'required' => ':attribute 為必填',
			'exists' => '番號錯誤',
		];

        $validator = Validator::make($input, $rules, $messages);
		$validator->setAttributeNames(['password' => '密碼', 'serial' => '番號']);

        if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }

        $credentials = [
            'serial' => Input::get('serial'),
            'password' => Input::get('password'),
        ];
		
        if (!Auth::attempt($credentials, Input::has('remember'))) {
            return Redirect::back()
                ->withInput()
                ->withErrors([
                    'credentials' => '密碼錯誤',
                ]);
        }                        
        return Redirect::to('/order');
	}
	
	public function doLogout() {
		Auth::logout();
		return Redirect::to('/order');
	}
	
}
