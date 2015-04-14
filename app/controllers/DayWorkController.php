<?php

class DayWorkController extends BaseController {

	public function show() {
		return View::make('dayWork');
	}
	
	public function store() {
		$result = Input::get('result');
		$date = Input::get('date');
		Log::info('get result ');
		Log::info($result);
		Log::info('get date');
		Log::info($date);
		$work = Work::create(['date' => date("Y-m-d", $date), 'content' => json_encode($result)]);
		// return $work->id;
	}
	
	public function load($id) {
		$work = Work::find($id);
		return Response::json(json_decode($work->result));
	} 

}
