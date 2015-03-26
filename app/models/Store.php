<?php

class Store extends Eloquent {
	
	protected $guarded = ['id'];
	public $timestamps = false;
	
	public function items() {
		return $this->hasMany('Item');
	}
	
	public function combos() {
		return $this->hasMany('Combo');
	}
}
