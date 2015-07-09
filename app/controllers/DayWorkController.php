<?php

class DayWorkController extends BaseController {

	public function show() {
		$today = date('Y-m-d');
		$work = Work::where('date', $today)->first();
		return View::make('dayWork', ['storable' => false]);
	}
	public function showStorable() {
		$today = date('Y-m-d');
		$work = Work::where('date', $today)->first();
		$defaults = Workdefault::all();
		return View::make('dayWork', ['storable' => true, 'defaults' => $defaults]);
	}
	public function showDefault() {
		$defaults = Workdefault::all();
		return View::make('dayWorkdefault', ['defaults' => $defaults]);
	}
	
	public function editDefault() {
		if (Input::get('password') != 'fireman') {
			return Redirect::back()->withErrors(['密碼錯誤']);
		}
		foreach (Workdefault::all() as $default) {
			$content = Input::get($default->code);
			$default->content = $content;
			$default->save();
		}
		return Redirect::back();
	}
	
	public function store() {
		if (Input::get('password') != 'fireman') {
			return -1;
		}
		$result = Input::get('result');
		$date = Input::get('date');
		$work = Work::where('date', $date)->first();
		if ($work == null) {
			$work = Work::create(['date' => $date, 'content' => json_encode($result)]);	
		} else {
			$work->content = json_encode($result);
			$work->save();
		}
		return $work->updated_at;
	}
	
	public function load() {
		$date = Input::get('date');
		$work = Work::where('date', $date)->first();
		return Response::json(json_decode($work));
	} 

}
