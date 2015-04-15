<?php

class DayWorkController extends BaseController {

	public function show() {
		$today = date('Y-m-d');
		$work = Work::where('date', $today)->first();
		$time = ($work == null)? '無' : $work->updated_at;
	
		return View::make('dayWork', ['lastModifiedTime' => $time]);
	}
	
	public function store() {
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
		$content = ($work == null) ? null : $work->content;
		return Response::json(json_decode($content));
	} 

}
