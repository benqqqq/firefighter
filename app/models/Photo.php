<?php

class Photo extends Eloquent {

	protected $guarded = ['id'];
	public $timestamps = false;
	
	public function store() {
		return $this->belongsTo('Store');
	}
	
}
