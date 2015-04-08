<?php

class OrderController extends BaseController {

	public function show() {
		$missions = Mission::where('isEnding', false)->with('user', 'store')->get();
		return View::make('order.show', ['missions' => $missions]);
	}
	
	public function showMission($id) {
		$mission = Mission::with('user', 'store.items.opts', 
			'store.combos.items.opts',
			'orders.user', 'orders.items', 
			'orders.orderCombos.combo',
			'orders.orderCombos.items')->find($id);
/* 		$statistic = json_encode($this->buildOrderStatistic($id)); */
		return View::make('order.showMission', ['mission' => $mission]);//, 'statistic' => $statistic]);
	}
	public function addOrder() {
		if (!Auth::check()) {
			return;
		}
		$type = Input::get('type');
		$missionId = Input::get('missionId');
		$id = Input::get('id');
		$opts = Input::get('optIds');
		
		$order = Order::where(['user_id' => Auth::id(), 'mission_id' => $missionId])->first();
		if ($order == null) {
			$order = Order::create(['user_id' => Auth::id(), 'mission_id' => $missionId]);	
		}		
		if ($type === 'item') {						
			$this->orderItem($id, $opts, $order);
		} else {
			$this->orderCombo($id, $opts, $order);
		}
		$this->update($missionId);		
	}	
	private function orderItem($id, $opts, $order) {
		$optStr = CtrlUtil::getOptStr($opts);
		$item = $order->items()->where('item_id', $id)->wherePivot('optStr', $optStr)->first();	
		if ($item == null) {
			$optPrice = CtrlUtil::getOptPrice($opts);
			$order->items()->attach($id, ['optStr' => $optStr, 'optPrice' => $optPrice]);
		}
		$order->incrementItemQuantity($id, $optStr);
		
	}
	private function orderCombo($id, $opts, $order) {
		$items = Combo::find($id)->items()->get();
		$optStr = '';
		foreach ($items as $item) {
			$optArr = !$opts ? null : array_key_exists($item->id, $opts) ? $opts[$item->id] : null;
			$optStr .= CtrlUtil::getOptStr($optArr) . ', ';
		}
			
		$orderCombo = OrderCombo::where(['combo_id' => $id, 'order_id' => $order->id, 'optStr' => $optStr])->first();
		if ($orderCombo == null) {
			$orderCombo =  OrderCombo::create(['combo_id' => $id, 'order_id' => $order->id, 'quantity' => 0, 'optStr' => $optStr]);			
			foreach ($items as $item) {					
				$optArr = !$opts ? null : array_key_exists($item->id, $opts) ? $opts[$item->id] : null;
				$optStr = CtrlUtil::getOptStr($optArr);
				$optPrice = CtrlUtil::getOptPrice($optArr);
				$orderCombo->items()->attach($item->id, ['optStr' => $optStr, 'optPrice' => $optPrice]);
				Log::info('Attach item ' . $item->id . ' with ' . $optStr . ' and ' . $optPrice);
			}
		}
		$orderCombo->quantity += 1;
		$orderCombo->save();
	}
	
	public function decreaseOrder() {
		if (!Auth::check()) {
			return;
		}
		$type = Input::get('type');
		$orderId = Input::get('orderId');
		$id = Input::get('id');
		$optStr = Input::get('optStr');
		
		$order = Order::find($orderId);		
		if ($order->user->id != Auth::id()) {
			return;
		}		
		if ($type === 'item') {
			$order->decrementItemQuantity($id, $optStr);
		}
		$this->update($order->mission->id);
	}
	
	private function update($missionId) {
		$data['orders'] = Order::where('mission_id', $missionId)->with('user', 'items', 
			'orderCombos.combo.items')->get();
/* 		$data['statistic'] = $this->buildOrderStatistic($missionId); */
		Event::fire(\Realtime\OrderUpdatedEventHandler::EVENT, json_encode($data));
	}
}
