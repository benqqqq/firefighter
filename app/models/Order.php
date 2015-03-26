<?php

class Order extends Eloquent {

	protected $guarded = ['id'];
	
	public function user() {
		return $this->belongsTo('User');
	}
	
	public function mission() {
		return $this->belongsTo('Mission');
	}
	
	public function orderItems() {
		return $this->hasMany('OrderItem');
	}
	
	public function orderCombos() {
		return $this->hasMany('OrderCombo');
	}
	
}
