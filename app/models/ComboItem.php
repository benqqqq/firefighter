<?php

class ComboItem extends Eloquent {

	public $table = 'comboItems';
	public $timestamps = false;
	protected $guarded = ['id'];
	
	public function combo() {
		return $this->belongsTo('Combo');
	}
	
	public function item() {
		return $this->belongsTo('Item');
	}
	
}
