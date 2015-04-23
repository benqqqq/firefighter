<?php

class OrderController extends BaseController {

	private $newItemIdMapping = [];

	public function show() {
		$missions = Mission::where('isEnding', false)->with('user', 'store')->orderBy('created_at', 'desc')->get();
		$stores = Store::orderBy('created_at', 'desc')->get();
		return View::make('order.show', ['missions' => $missions, 'stores' => $stores]);
	}
	
	public function showMission($id) {
		$mission = Mission::with('user', 'store.items.opts', 
			'store.combos.items.opts',
			'orders.user', 'orders.items', 
			'orders.orderCombos.combo',
			'orders.orderCombos.items',
			'store.photos')->find($id);
		$statistic = json_encode($this->buildOrderStatistic($id));
		
		$orders = $this->getOrders($id);
		
		return View::make('order.showMission', ['mission' => $mission, 'orders' => $orders, 'statistic' => $statistic]);
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
					$result['price']['total'] += ($orderCombo->combo->basePrice() + $orderCombo->combo->price + $orderCombo->optPrice) * $quantity;
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
		$userId = Input::get('userId');
		if ($userId == null) {
			return '請選擇番號';
		}
		
		$type = Input::get('type');
		$missionId = Input::get('missionId');
		$id = Input::get('id');
		$opts = Input::get('optIds');
		$order = Order::where(['user_id' => $userId, 'mission_id' => $missionId])->first();
		if ($order == null) {
			$order = Order::create(['user_id' => $userId, 'mission_id' => $missionId, 'remark' => '']);	
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
		$userId = Input::get('userId');
		if ($userId == null) {
			return '請選擇番號';
		}
		
		$type = Input::get('type');
		$orderId = Input::get('orderId');
		$id = Input::get('id');
		$optStr = Input::get('optStr');
		
		$order = Order::find($orderId);		
		if ($order->user->id != $userId) {
			/* return '非本人不可刪除'; */
		}		
		if ($type === 'item') {
			$order->decrementItemQuantity($id, $optStr);
		} else {
			$order->decrementComboQuantity($id);
		}
		$this->update($order->mission->id);
	}
	
	private function update($missionId) {
		$data['orders'] = $this->getOrders($missionId);
		$data['statistic'] = $this->buildOrderStatistic($missionId);
		Event::fire(\Realtime\OrderUpdatedEventHandler::EVENT, json_encode($data));
	}
	private function getOrders($missionId) {
		$orders = Order::where('mission_id', $missionId)->with('user', 'items', 
			'orderCombos.combo.items', 'orderCombos.items')->get();	
		$this->buildComboBasePrice($orders);
		return $orders;
	}

	private function buildComboBasePrice($orders) {
		if ($orders == null) {
			return;
		}
		foreach ($orders as $order) {
			foreach ($order->orderCombos as $orderCombo) {
				$combo = $orderCombo->combo;
				$combo->basePrice = (int)$combo->basePrice();
			}
		}	
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
	
	public function createStore() {
		return View::make('order.createStore');
	}
	
	public function doCreateStore() {
		$input = Input::only(['name', 'phone', 'address', 'detail']);
		$validator = $this->validateStoreInfo($input);
		if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
		Store::create($input);
		
		return Redirect::to('order');
	}
	private function validateStoreInfo($input) {
		$rules = [
            'name' => 'required'
        ];
        $messages = [
			'required' => ':attribute 為必填'
		];
		$validator = Validator::make($input, $rules, $messages);
		$validator->setAttributeNames(['name' => '名稱']);
		return $validator;
	}
	
	public function createMission($id) {
		$store = Store::where('id', $id)->with('photos')->first();
		$items = $store->items()->with('opts')->get();		
		$combos = $store->combos()->with('items.opts')->get();
		Log::info($store->name);
		foreach ($combos as $combo) {
			$combo->basePrice = (int)$combo->basePrice();
			$combo->baseOptPrice = (int)$combo->baseOptPrice();
		}
		return View::make('order.createMission', ['store' => $store, 'items' => $items, 'combos' => $combos]);
	}
	
	public function doCreateMission($id) {
		$userId = Input::get('userId');
		if ($userId == null) {
			return Redirect::back()->withMessage('請選擇番號');
		}
		$mission = Mission::create(['user_id' => $userId, 'store_id' => $id, 'name' => Input::get('name')]);
		
		return Redirect::to('order/' . $mission->id);
	}
	
	public function deleteMission($id) {
		Mission::find($id)->delete();
		return Redirect::to('order');
	}
	
	public function editStore($id) {
		$store = Store::where('id', $id)->with('photos')->first();
		$items = $store->items()->with('opts')->get();		
		$combos = $store->combos()->with('items.opts')->get();
		
		foreach ($combos as $combo) {
			$combo->basePrice = (int)$combo->basePrice();
			$combo->baseOptPrice = (int)$combo->baseOptPrice();
			$combo->editPrice = $combo->basePrice + $combo->baseOptPrice + $combo->price;
		}
		
		return View::make('order.editStore', ['store' => $store, 'items' => $items, 'combos' => $combos]);
	}
	
	public function doEditStore($id) {
		$input = Input::only(['name', 'phone', 'address', 'detail']);
		$validator = $this->validateStoreInfo($input);
		if ($validator->fails()) {
            return Redirect::back()->withInput()->withErrors($validator);
        }
		Store::find($id)->update($input);
		
		$items = json_decode(Input::get('items'));
		$combos = json_decode(Input::get('combos'));
		$this->storeItems($id, $items);
		$this->storeCombos($id, $combos);
		$this->storePhotos($id);
		return Redirect::back();
	}
	
	public function deleteStore($id) {
		File::deleteDirectory(public_path() . '/photos/' . $id);
		Store::find($id)->delete();
		return Redirect::to('order');
	}
	
	private function storeItems($storeId, $items) {
		DB::transaction(function() use ($storeId, $items) {
			$inputItemIds = [];
			$inputOptIds = [];
			foreach ($items as $item) {
				if ($item->id == -1) {
					$createdItem = Item::create(['store_id' => $storeId, 
						'name' => $item->name, 'price' => $item->price, 'optStr' => '', 'optPrice' => 0]);
					$this->newItemIdMapping[$item->newItemId] = $createdItem->id;
					if (isset($item->opts)) {
						foreach ($item->opts as $opt) {
							$createdOpt = Opt::create(['item_id' => $createdItem->id, 'name' => $opt->name, 'price' => $opt->price]);
							array_push($inputOptIds, $createdOpt->id);
						}
						$createdItem->optStr = $item->optStr;
						$createdItem->optPrice = $item->optPrice;
						$createdItem->save();
					}
					array_push($inputItemIds, $createdItem->id);
				} else {
					$existItem = Item::find($item->id);
					$existItem->name = $item->name;
					$existItem->price = $item->price;
					$existItem->optStr = $item->optStr;
					$existItem->optPrice = $item->optPrice;
					$existItem->save();
					
					foreach ($item->opts as $opt) {
						if ($opt->id == -1) {
							$createdOpt = Opt::create(['item_id' => $existItem->id, 'name' => $opt->name, 'price' => $opt->price]);
							array_push($inputOptIds, $createdOpt->id);						
						} else {
							$existOpt = Opt::find($opt->id);
							$existOpt->name = $opt->name;
							$existOpt->price = $opt->price;
							$existOpt->save();
							array_push($inputOptIds, $opt->id);
						}
					}
					array_push($inputItemIds, $existItem->id);
				}
			}
			
			$inputItemIds = count($inputItemIds) > 0 ? $inputItemIds : [-1];
			$inputOptIds = count($inputOptIds) > 0 ? $inputOptIds : [-1];
			Store::where('id', $storeId)->first()->items()->whereNotIn('id', $inputItemIds)->delete();
			Store::where('id', $storeId)->first()->opts()->whereNotIn('opts.id', $inputOptIds)->delete();
		});
	}
	
	private function storeCombos($storeId, $combos) {
		DB::transaction(function() use ($storeId, $combos) {
			$inputComboIds = [];
			foreach ($combos as $combo) {
				if ($combo->id == -1) {
					$createdCombo = Combo::create(['store_id' => $storeId, 'name' => $combo->name, 'price' => $combo->price]);
					if (isset($combo->items)) {
						foreach($combo->items as $item) {
							$item->id = ($item->id == -1) ? $this->newItemIdMapping[$item->newItemId] : $item->id;						
							$createdCombo->items()->attach($item->id, ['optStr' => $item->pivot->optStr, 'optPrice' => $item->pivot->optPrice]);
						}
					}
					array_push($inputComboIds, $createdCombo->id);
				} else {
					$existCombo = Combo::find($combo->id);
					$existCombo->name = $combo->name;
					$existCombo->price = $combo->price;
					$existCombo->save();
					$existCombo->items()->detach();
					foreach($combo->items as $item) {
						$item->id = ($item->id == -1) ? $this->newItemIdMapping[$item->newItemId] : $item->id;						
						$existCombo->items()->attach($item->id, ['optStr' => $item->pivot->optStr, 'optPrice' => $item->pivot->optPrice]);
					}
					array_push($inputComboIds, $existCombo->id);
				}
			}
			$inputComboIds = count($inputComboIds) > 0 ? $inputComboIds : [-1];
			Store::where('id', $storeId)->first()->combos()->whereNotIn('id', $inputComboIds)->delete();
		});
	}
	
	private function storePhotos($storeId) {
		$files = Input::file('photos');
		if ($files[0] != NULL) {
			$dir =  public_path() . '/photos/' . $storeId;					
			File::deleteDirectory($dir);
			File::makeDirectory($dir);									
			Photo::where('store_id', $storeId)->delete();
			for ($i = 0; $i < count($files); ++$i) {
				$file = $files[$i];					
				$name = $i . '.' . $file->getClientOriginalExtension();
				$file->move($dir, $name);
				Photo::create(['store_id' => $storeId, 'src' => "photos/$storeId/" . $name]);
			}			
		}
	}
}
