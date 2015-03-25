<?php

class Combo extends Eloquent {

	protected $guarded = ['id'];
	public $timestamps = false;
	
	public function store() {
		return $this->belongsTo('Store');
	}
	
	public function items() {
		return $this->hasMany('Item');
	}
	
}
