<?php

class OrderSeeder extends Seeder {

	public function run() {
		$this->clearTables();
		$this->createUser();
		$this->createStore();
		$this->createEvent();	
	}
	
	private function clearTables() {
		DB::table('users')->delete();
		DB::table('combos')->delete();	
		DB::table('events')->delete();	
		DB::table('items')->delete();	
		DB::table('orders')->delete();	
		DB::table('sorders')->delete();	
		DB::table('stores')->delete();
	}
	
	private function createUser() {
		User::create(['name' => '管理員', 'serial' => 0, 'password' => Hash::make('admin')]);
		User::create(['name' => 'AAA', 'serial' => 'A', 'password' => Hash::make('admin')]);
	}
	
	private function createStore() {
		$store1 = Store::create(['name' => '東方美', 'phone' => 'xxxx-xxxx', 'address' => '地址...']);
		Store::create(['name' => '豪客', 'phone' => 'xxxx-xxxx', 'address' => '地址...']);
		Store::create(['name' => '麥味登', 'phone' => 'xxxx-xxxx', 'address' => '地址...']);
		
		$item1 = new Item(['name' => '火腿起司堡', 'price' => 30]);		
		$item2 = new Item(['name' => '烤三明治', 'price' => 30]);
		$item3 = new Item(['name' => '火腿', 'price' => 20]);
		$opt1 = new Option(['name' => '加蛋', 'price' => 5]);
		$opt2 = new Option(['name' => '冰', 'price' => 0]);
		$opt3 = new Option(['name' => '熱', 'price' => 0]);
		$opt4 = new Option(['name' => '小杯', 'price' => 0]);
		$opt5 = new Option(['name' => '中杯', 'price' => 5]);
		$opt6 = new Option(['name' => '大杯', 'price' => 10]);
				
		$drink1 = new Item(['name' => '奶茶', 'price' => 20, 'isIndependent' => false]);
		$drink2 = new Item(['name' => '紅茶', 'price' => 15, 'isIndependent' => false]);
		
		$store1->items()->saveMany([$item1, $item2, $item3, $drink1, $drink2]);
		
		$item1->opts()->save($opt1);
		$item2->opts()->save($opt1);
		$drink1->opts()->saveMany([$opt2, $opt3, $op4, $opt5, $opt6]);
		$drink2->opts()->saveMany([$opt2, $opt3, $op4, $opt5, $opt6]);
				
		$combo1 = new Combo(['name' => 'A套餐', 'price' => 50]);
		$combo2 = new Combo(['name' => 'B套餐', 'price' => 55]);		
		$store1->combos()->saveMany([$combo1, $combo2]);
		$combo1->items()->saveMany([$item1, $item2, $drink1]);
		$combo2->items()->saveMany([$item3, $drink2]);		
	}
	
	private function createEvent() {
		
	}

}
