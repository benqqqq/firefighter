<?php

class Store extends Eloquent {
	
	protected $guarded = ['id'];
	
	public function items() {
		return $this->hasMany('Item');
	}
	
	public function combos() {
		return $this->hasMany('Combo');
	}

	public function photos() {
		return $this->hasMany('Photo');
	}
	
	public function categories() {
		return $this->hasMany('Category');
	}
	
	public function opts() {
		return $this->hasManyThrough('Opt', 'Item');
	}
		
}
