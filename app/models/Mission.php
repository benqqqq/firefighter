<?php

class Mission extends Eloquent {

	protected $guarded = ['id'];
	
	public function user() {
		return $this->belongsTo('User');
	}
	
	public function store() {
		return $this->belongsTo('Store');
	}
	
	public function orders() {
		return $this->hasMany('Order');
	}
	
	public function items() {
		return $this->hasManyThrough('Item', 'Order')->withPivot('optStr', 'optPrice');
	}
	
	public function orderCombos() {
		return $this->hasManyThrough('OrderCombo', 'Order');
	}
}
