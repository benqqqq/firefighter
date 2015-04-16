<?php

class OrderController extends BaseController {

	public function show() {
		$missions = Mission::where('isEnding', false)->with('user', 'store')->get();
		$stores = Store::all();
		return View::make('order.show', ['missions' => $missions, 'stores' => $stores]);
	}
	
	public function showMission($id) {
		$mission = Mission::with('user', 'store.items.opts', 
			'store.combos.items.opts',
			'orders.user', 'orders.items', 
			'orders.orderCombos.combo',
			'orders.orderCombos.items')->find($id);
		$statistic = json_encode($this->buildOrderStatistic($id));
		return View::make('order.showMission', ['mission' => $mission, 'myOrder' => $this->getMyOrder($id), 
			'otherOrders' => $this->getOtherOrders($id), 'statistic' => $statistic]);
	}
	
	private function buildOrderStatistic($id) {
		$orderIds = Mission::find($id)->orders()->lists('id');
		
		$result['item'] = [];		
		$result['combo'] = [];
		$result['price'] = ['total' => 0, 'paid' => 0];
		if (sizeof($orderIds) > 0) {
			$itemIds = DB::table('item_order')->whereIn('order_id', $orderIds)->groupBy('item_id')->lists('item_id');
			foreach ($itemIds as $itemId) {
				$datas = DB::table('item_order')->whereIn('order_id', $orderIds)->where('item_id', $itemId)->groupBy('optStr')->get();
				foreach ($datas as $data) {
					$item = Item::find($data->item_id);
					$quantity = DB::table('item_order')->whereIn('order_id', $orderIds)
						->where(['item_id' => $itemId, 'optStr' => $data->optStr])->sum('quantity');
					array_push($result['item'], ['name' => $item->name, 'optStr' => $data->optStr, 'quantity' => $quantity]);
					$result['price']['total'] += ($item->price + $data->optPrice) * $quantity;
				}						
			}	
			$comboIds = OrderCombo::whereIn('order_id', $orderIds)->groupBy('combo_id')->lists('combo_id');
			foreach ($comboIds as $comboId) {
				$orderCombos = OrderCombo::whereIn('order_id', $orderIds)->where('combo_id', $comboId)->groupBy('optStr')->get();
				foreach ($orderCombos as $orderCombo) {
					$quantity = OrderCombo::whereIn('order_id', $orderIds)
						->where(['combo_id' => $comboId, 'optStr' => $orderCombo->optStr])->sum('quantity');	
					$items = [];
					foreach ($orderCombo->items as $item) {
						array_push($items, ['name' => $item->name, 'optStr' => $item->pivot->optStr]);
					}
					array_push($result['combo'], ['name' => $orderCombo->combo->name, 'items' => $items, 'quantity' => $quantity]);
					$result['price']['total'] += ($orderCombo->combo->price + $orderCombo->optPrice) * $quantity;
				}				
			}
			foreach ($orderIds as $orderId) {
				$order = Order::find($orderId);
				$result['price']['paid'] += $order->paid;
			}
		}
		
		return $result;
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
			$order = Order::create(['user_id' => Auth::id(), 'mission_id' => $missionId, 'remark' => '']);	
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
		$optPrice = 0;
		foreach ($items as $item) {
			$optArr = !$opts ? null : array_key_exists($item->id, $opts) ? $opts[$item->id] : null;
			$optStr .= CtrlUtil::getOptStr($optArr) . ', ';
			$optPrice += CtrlUtil::getOptPrice($optArr);
		}
			
		$orderCombo = OrderCombo::where(['combo_id' => $id, 'order_id' => $order->id, 'optStr' => $optStr])->first();
		if ($orderCombo == null) {
			$orderCombo =  OrderCombo::create(['combo_id' => $id, 'order_id' => $order->id, 'quantity' => 0
				, 'optStr' => $optStr, 'optPrice' => $optPrice]);
			foreach ($items as $item) {					
				$optArr = !$opts ? null : array_key_exists($item->id, $opts) ? $opts[$item->id] : null;
				$optStr = CtrlUtil::getOptStr($optArr);
				$optPrice = CtrlUtil::getOptPrice($optArr);
				$orderCombo->items()->attach($item->id, ['optStr' => $optStr, 'optPrice' => $optPrice]);
			}
		}
		$orderCombo->quantity += 1;
		$orderCombo->save();
	}
	
	public function decrementOrder() {
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
		} else {
			$order->decrementComboQuantity($id);
		}
		$this->update($order->mission->id);
	}
	
	private function update($missionId) {
/*
		if (Auth::check()) {
			$data['myOrder'] = Order::where(['mission_id' => $missionId, 'user_id' => Auth::id()])->with('user', 'items', 
			'orderCombos.combo.items', 'orderCombos.items')->get();	
			$data['OtherOrders'] = Order::where('mission_id', $missionId)->where('user_id', '!=', Auth::id())->with('user', 'items', 
			'orderCombos.combo.items', 'orderCombos.items')->get();
		} else {
			$data['myOrder'] = null;
			$data['OtherOrders'] = Order::where('mission_id', $missionId)->with('user', 'items', 
			'orderCombos.combo.items', 'orderCombos.items')->get();
		}
*/
		$data['myOrder'] = $this->getMyOrder($missionId);		
		$data['otherOrders'] = $this->getOtherOrders($missionId);
		$data['statistic'] = $this->buildOrderStatistic($missionId);
		Event::fire(\Realtime\OrderUpdatedEventHandler::EVENT, json_encode($data));
	}
	private function getMyOrder($missionId) {
		return Auth::check() ? Order::where(['mission_id' => $missionId, 'user_id' => Auth::id()])->with('user', 'items', 
			'orderCombos.combo.items', 'orderCombos.items')->get() : null;	
	}
	private function getOtherOrders($missionId) {
		return Auth::check() ? $data['OtherOrders'] = Order::where('mission_id', $missionId)->where('user_id', '!=', Auth::id())->with('user', 'items', 'orderCombos.combo.items', 'orderCombos.items')->get() :
				Order::where('mission_id', $missionId)->with('user', 'items', 'orderCombos.combo.items', 'orderCombos.items')->get();				
	}
	
	public function paid() {
		$order = Order::find(Input::get('orderId'));
		$order->paid = Input::get('paid');
		$order->save();
		$this->update($order->mission->id);
	}
	public function remark() {
		$order = Order::find(Input::get('orderId'));
		$order->remark = Input::get('remark');
		$order->save();
		$this->update($order->mission->id);
	}	
}
