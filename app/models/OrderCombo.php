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
	
	public function items() {
		return $this->belongsToMany('Item', 'item_ordercombo', 'ordercombo_id', 'item_id')->withPivot('optStr', 'optPrice');
	}
	
	public function decrementQuantity() {
		--$this->quantity;
		$this->save();
		if ($this->quantity < 1) {
			$this->delete();
		}
	}	
	
}
