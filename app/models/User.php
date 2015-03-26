<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;
	protected $guarded = array('id', 'remember_token');
	protected $hidden = array('password', 'remember_token');
	public $timestamps = false;
	
	
	public function orders() {
		return $this->hasMany('Order');
	}

}
