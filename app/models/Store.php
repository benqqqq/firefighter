<?php

class Store extends Eloquent {
	
	protected $guarded = ['id'];
	
	public function items() {
		return $this->hasMany('Item');
	}
	
	public function combos() {
		return $this->hasMany('Combo');
	}

	public function photos() {
		return $this->hasMany('Photo');
	}
	
	public function categories() {
		return $this->hasMany('Category');
	}
	
	public function missions() {
		return $this->hasMany('Mission');
	}
	
	public function unCategoryItems() {
		$excludeIds = DB::table('category_item')->lists('item_id');
		$excludeIds = count($excludeIds) > 0 ? $excludeIds : [-1];
		return $this->items()->whereNotIn('id', $excludeIds);
	}
	
	public function opts() {
		return $this->hasManyThrough('Opt', 'Item');
	}
	
	public function lastOrder() {
		$lastMission = $this->missions()->orderBy('created_at', 'desc')->skip(1)->first();		
		return $lastMission->orders();
	}
	
	public function recentOrders() {
		$recentMissions = $this->missions()->where('isDelete', 0)->orderBy('created_at', 'desc')->skip(1)->take(10)->get();
		$resultOrders = [];
		$max = 10;
		foreach ($recentMissions as $mission) {			
			$orders = $mission->orders()->get();
			foreach ($orders as $order) {				
				$serial = $order->user->serial;
				if (!isset($resultOrders[$serial])) {
					$resultOrders[$serial] = ['items' => [], 'combos' => []];
				}
				
				foreach ($order->items as $item) {
					$itemId = $item->id;
					if (!isset($resultOrders[$serial]['items'][$itemId]) && 
						(count($resultOrders[$serial]['items']) + count($resultOrders[$serial]['combos'])) < $max) {
						$resultOrders[$serial]['items'][$itemId] = $item;
					}
				}
				foreach ($order->orderCombos as $orderCombo) {
					$comboId = $orderCombo->combo->id;
					if (!isset($resultOrders[$serial]['combos'][$comboId]) && 
						(count($resultOrders[$serial]['items']) + count($resultOrders[$serial]['combos'])) < $max) {
						$resultOrders[$serial]['combos'][$comboId] = $orderCombo->combo;
					}
				}
			}
		}
		return $resultOrders;		
	}
		
}
