<?php

class OrderController extends BaseController {

	public function show() {
		return View::make('order.show');
	}
	
	public function addOrder() {
		Event::fire(\Realtime\OrderUpdatedEventHandler::EVENT, ['test']);
	}	

}
