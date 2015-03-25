<?php

class DayWorkController extends BaseController {

	public function show() {
		return View::make('dayWork');
	}
	
	public function store() {
		$result = Input::get('result');
		$work = Work::create(['result' => json_encode($result)]);
		return $work->id;
	}
	
	public function load($id) {
		$work = Work::find($id);
		return Response::json(json_decode($work->result));
	} 

}
