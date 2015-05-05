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
		return $this->hasManyThrough('Item', 'Order');
	}
	
	public function orderCombos() {
		return $this->hasManyThrough('OrderCombo', 'Order');
	}

	public function isReadOnly() {		
		$isPast = new Date($this->updated_at) < new Date('-30 min');
		return $this->isEnding && $isPast;
	}
}
