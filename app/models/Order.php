<?php

class Order extends Eloquent {

	protected $guarded = ['id'];
	
	public function user() {
		return $this->belongsTo('User');
	}
	
	public function mission() {
		return $this->belongsTo('Mission');
	}
	
	public function items() {
		return $this->belongsToMany('Item')->withPivot('optStr', 'optPrice', 'quantity');
	}
	
	public function orderCombos() {
		return $this->hasMany('OrderCombo');
	}
	
	public function incrementItemQuantity($id, $optStr) {
		DB::table('item_order')->where(['order_id' => $this->id, 'item_id' => $id, 'optStr' => $optStr])->increment('quantity');
	}
	public function decrementItemQuantity($id, $optStr) {
		$quantity = DB::table('item_order')->where(['order_id' => $this->id, 'item_id' => $id, 'optStr' => $optStr])->lists('quantity')[0];
		if ($quantity <= 1) {
			DB::table('item_order')->where(['order_id' => $this->id, 'item_id' => $id, 'optStr' => $optStr])->delete();
		}
		DB::table('item_order')->where(['order_id' => $this->id, 'item_id' => $id, 'optStr' => $optStr])->decrement('quantity');		
	}
}
