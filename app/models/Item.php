<?php

class Item extends Eloquent {

	protected $guarded = ['id'];
	public $timestamps = false;
	
	public function store() {
		return $this->belongsTo('Store');
	}
	
	public function opts() {
		return $this->hasMany('Opt');
	}
	
	public function setOpt($optIds) {
		CtrlUtil::setOpt($this, $optIds);
		$this->save();
	}
	
	public function frequency() {
		/*
$storeFrequency = DB::table('item_order')
			->join('items', 'item_order.item_id', '=', 'items.id')
			->where('store_id', $this->store_id)->count();		
return ($storeFrequency == 0)? 0 : $itemFrequency / $storeFrequency;
*/
		$category = DB::table('category_item')->where('item_id', $this->id)->select('category_id')->first();
		if ($category == null) {
			return 0;
		} else {
			$categoryId = $category->category_id;
			$categoryFrequency = DB::table('item_order')
				->join('category_item', 'item_order.item_id', '=', 'category_item.item_id')
				->where('category_id', $categoryId)->count();		
			$itemFrequency = DB::table('item_order')->where('item_id', $this->id)->count();
			return ($categoryFrequency == 0) ? 0 : $itemFrequency / $categoryFrequency;
		}		
	}
	
	public function hotColor() {
		$frequency = $this->frequency();
		if ($frequency >= 0.2) {
			return 'text-hottest';
		} else if ($frequency >= 0.1) {
			return 'text-hotter';
		} else {
			return '';
		}
	}
}
