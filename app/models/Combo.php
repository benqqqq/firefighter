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
	
	public function frequency() {
		$storeFrequency = DB::table('ordercombos')
			->join('combos', 'ordercombos.combo_id', '=', 'combos.id')
			->where('store_id', $this->store_id)->count();
		$comboFrequency = DB::table('ordercombos')->where('combo_id', $this->id)->count();
		return ($storeFrequency == 0)? 0 : $comboFrequency / $storeFrequency;
	}
	public function hotColor() {
		$frequency = Cache::remember('frequency-c-'.$this->id, 60, function() {
            return $this->frequency();
        });

		if ($frequency >= 0.2) {
			return 'text-hottest';
		} else if ($frequency >= 0.1) {
			return 'text-hotter';
		} else {
			return '';
		}
	}
}
