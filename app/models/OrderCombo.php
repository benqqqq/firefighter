<?php

class OrderCombo extends Eloquent {
	
	public $table = 'orderCombos';
	public $timestamps = false;
	protected $guarded = ['id'];
	
	public function order() {
		return $this->belongsTo('Order');
	}

	public function combo() {
		return $this->belongsTo('Combo');
	}	
	
	
}
