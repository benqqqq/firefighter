<?php

class OrderItem extends Eloquent {

	public $table = 'orderItems';
	public $timestamps = false;
	protected $guarded = ['id'];
	
	public function order() {
		return $this->belongsTo('Order');
	}

	public function item() {
		return $this->belongsTo('Item');
	}	
	
	
}
