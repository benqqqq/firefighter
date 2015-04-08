<?php

class Item extends Eloquent {

	protected $guarded = ['id'];
	public $timestamps = false;
	
	public function store() {
		return $this->belongsTo('Store');
	}
	
	public function opts() {
		return $this->hasMany('Opt');
	}
	
	public function setOpt($optIds) {
		CtrlUtil::setOpt($this, $optIds);
		$this->save();
	}
}
