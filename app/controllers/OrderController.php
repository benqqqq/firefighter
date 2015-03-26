<?php

class OrderController extends BaseController {

	public function show() {
		$missions = Mission::where('isEnding', false)->with('user', 'store')->get();
		return View::make('order.show', ['missions' => $missions]);
	}
	
	public function showMission($id) {
		$mission = Mission::with('user', 'store', 'store.items', 'store.combos', 'store.combos.comboItems', 
			'orders', 'orders.user', 'orders.orderItems', 'orders.orderItems.item', 
			'orders.orderCombos', 'orders.orderCombos.combo', 'orders.orderCombos.combo.comboItems')->find($id);
		
		return View::make('order.showMission', ['mission' => $mission]);
	}
	
	public function addOrder() {
		Event::fire(\Realtime\OrderUpdatedEventHandler::EVENT, ['test']);
	}	

}
