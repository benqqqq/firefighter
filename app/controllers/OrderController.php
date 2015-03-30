<?php

class OrderController extends BaseController {

	public function show() {
		$missions = Mission::where('isEnding', false)->with('user', 'store')->get();
		return View::make('order.show', ['missions' => $missions]);
	}
	
	public function showMission($id) {
		$mission = Mission::with('user', 'store', 'store.items', 
			'store.combos.comboItems.item.opts',
			'orders.user', 'orders.orderItems.item', 
			'orders.orderCombos.combo',
			'orders.orderCombos.orderComboItems.item')->find($id);
		$statistic = json_encode($this->buildOrderStatistic($id));
		return View::make('order.showMission', ['mission' => $mission, 'statistic' => $statistic]);
	}
	
	private function buildOrderStatistic($id) {
		$itemIds = Mission::find($id)->orderItems()->groupBy('item_id')->lists('item_id');		
		$result['item'] = [];
		foreach ($itemIds as $itemId) {
			$orderItems = Mission::find($id)->orderItems()->where('item_id', $itemId)->groupBy('optStr')->get();
			foreach ($orderItems as $orderItem) {
				$quantity = Mission::find($id)->orderItems()->where(['item_id' => $itemId, 'optStr' => $orderItem->optStr])->sum('quantity');
				array_push($result['item'], ['name' => $orderItem->item->name, 'optStr' => $orderItem->optStr, 'quantity' => $quantity]);
			}
		}
		
		$comboIds = Mission::find($id)->orderCombos()->groupBy('combo_id')->lists('combo_id');
		$result['combo'] = [];
		foreach ($comboIds as $comboId) {
			// Find the combo_ids ( maybe with different optStr )
			$orderCombos = Mission::find($id)->orderCombos()->where('combo_id', $comboId)->groupBy('optStr')->get();
			foreach ($orderCombos as $orderCombo) {
				$quantity = Mission::find($id)->orderCombos()->where(['combo_id' => $comboId, 'optStr' => $orderCombo->optStr])->sum('quantity');
				$items = [];				
				foreach($orderCombo->orderComboItems as $orderComboItem) {
					array_push($items, ['name' => $orderComboItem->item->name, 'optStr' => $orderComboItem->optStr]);
				}				
				array_push($result['combo'], ['name' => $orderCombo->combo->name, 'items' => $items, 'quantity' => $quantity]);
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
			$order = Order::create(['user_id' => Auth::id(), 'mission_id' => $missionId]);	
		}		
		if ($type === 'item') {						
			$optStr = CtrlUtil::getOptStr($opts);
			$orderItem = OrderItem::where(['item_id' => $id, 'order_id' => $order->id, 'optStr' => $optStr])->first();			
			if ($orderItem == null) {
				$orderItem = OrderItem::create(['item_id' => $id, 'order_id' => $order->id, 'quantity' => 0]);	
				$orderItem = $orderItem->setOpt($opts);
			}
			$orderItem->quantity += 1;
			$orderItem->save();
		} else {				
			$comboItems = ComboItem::where('combo_id', $id)->get();
			$optStr = '';
			foreach ($comboItems as $comboItem) {
				$optArr = !$opts ? null : array_key_exists($comboItem->id, $opts) ? $opts[$comboItem->id] : null;
				$optStr .= CtrlUtil::getOptStr($optArr) . ', ';
			}
			
			$orderCombo = OrderCombo::where(['combo_id' => $id, 'order_id' => $order->id, 'optStr' => $optStr])->first();
			if ($orderCombo == null) {
				$orderCombo =  OrderCombo::create(['combo_id' => $id, 'order_id' => $order->id, 'quantity' => 0, 'optStr' => $optStr]);
				foreach ($comboItems as $comboItem) {					
					$optArr = !$opts ? null : array_key_exists($comboItem->id, $opts) ? $opts[$comboItem->id] : null;
					$orderComboItem = new OrderComboItem(['order_combo_id' => $orderCombo->id, 'item_id' => $comboItem->item->id]);				
					$orderComboItem = CtrlUtil::setOpt($orderComboItem, $optArr);
					$orderCombo->orderComboItems()->save($orderComboItem);
				}
			}
			$orderCombo->quantity += 1;
			$orderCombo->save();
		}		
		$this->update($missionId);
		
	}	
	public function decreaseOrder() {
		if (!Auth::check()) {
			return;
		}
		$type = Input::get('type');
		$id = Input::get('id');
		$orderThing = ($type == 'item') ? OrderItem::find($id) : OrderCombo::find($id);
		if ($orderThing->order->user->id != Auth::id()) {
			return;
		}
		$missionId = $orderThing->order->mission->id;
		$orderThing->decrease();		
		$this->update($missionId);		
	}
	private function update($missionId) {
		$data['orders'] = Order::where('mission_id', $missionId)->with('user', 'orderItems.item', 
			'orderCombos.combo', 'orderCombos.orderComboItems.item')->get();
		$data['statistic'] = $this->buildOrderStatistic($missionId);
		Event::fire(\Realtime\OrderUpdatedEventHandler::EVENT, json_encode($data));
	}

}
