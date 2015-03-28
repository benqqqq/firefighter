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
		
		return View::make('order.showMission', ['mission' => $mission]);
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
		if ($type == 'item') {						
			$inputOrderItem = new OrderItem(['item_id' => $id, 'order_id' => $order->id, 'quantity' => 0]);
			$inputOrderItem = CtrlUtil::setOpt($inputOrderItem, $opts);
			$orderItem = OrderItem::where(['item_id' => $id, 'order_id' => $order->id, 'optStr' => $inputOrderItem->optStr])->first();			
			if ($orderItem == null) {
				$inputOrderItem->save();
				$orderItem = $inputOrderItem;
			}
			$orderItem->quantity += 1;
			$orderItem->save();
		} else {						
			$orderCombos = OrderCombo::where(['combo_id' => $id, 'order_id' => $order->id])->get();
			$combo = Combo::find($id);
			$isCreated = false;
			foreach ($orderCombos as $orderCombo) {
				$isExist = true;
				foreach ($combo->comboItems as $comboItem) {
					$optArr = !$opts ? null : array_key_exists($comboItem->id, $opts) ? $opts[$comboItem->id] : null;
					$inputOptStr = CtrlUtil::getOptStr($optArr);
					$orderComboItem = OrderComboItem::where(['order_combo_id' => $orderCombo->id, 'item_id' => $comboItem->item->id])->first();
					if ($orderComboItem == null || $orderComboItem->optStr !== $inputOptStr) {
						$isExist = false;
						break;
					}
				}
				if ($isExist) {
					$orderCombo->quantity += 1;
					$orderCombo->save();
					$isCreated = true;
					break;
				}
			}
			if (!$isCreated) {
				$orderCombo = OrderCombo::create(['combo_id' => $id, 'order_id' => $order->id]);
				foreach ($combo->comboItems as $comboItem) {					
					$orderComboItem = new OrderComboItem(['order_combo_id' => $orderCombo->id, 'item_id' => $comboItem->item->id]);
					$optArr = !$opts ? null : array_key_exists($comboItem->id, $opts) ? $opts[$comboItem->id] : null;
					$orderComboItem = CtrlUtil::setOpt($orderComboItem, $optArr);
					$orderCombo->orderComboItems()->save($orderComboItem);
				}
			}
		}		
		$orders = Order::where('mission_id', $missionId)->with('user', 'orderItems.item', 
			'orderCombos.combo', 'orderCombos.orderComboItems.item')->get();
		Event::fire(\Realtime\OrderUpdatedEventHandler::EVENT, $orders);
	}	

}
