<?php

class OrderComboItem extends Eloquent {

	public $table = 'orderComboItems';
	public $timestamps = false;
	protected $guarded = ['id'];
	
	public function orderCombo() {
		return $this->belongsTo('OrderCombo');
	}

	public function item() {
		return $this->belongsTo('Item');
	}	
	
	
}
