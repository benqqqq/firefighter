<?php

class Combo extends Eloquent {

	protected $guarded = ['id'];
	public $timestamps = false;
	
	public function store() {
		return $this->belongsTo('Store');
	}
	
	public function items() {
		return $this->belongsToMany('Item')->withPivot('optStr', 'optPrice');
	}
	
	public function basePrice() {
		return $this->items()->sum('price');
	}
	public function baseOptPrice() {
		return DB::table('combo_item')->where('combo_id', $this->id)->sum('optPrice');
	}
}
