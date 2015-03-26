<?php

class Opt extends Eloquent {

	protected $guarded = ['id'];
	public $timestamps = false;
	
	public function item() {
		return $this->belongsTo('Item');
	}
	
}
