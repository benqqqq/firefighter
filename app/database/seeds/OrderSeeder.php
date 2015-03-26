<?php

class OrderSeeder extends Seeder {

	public function run() {
		$this->clearTables();
		$this->createUser();
		$this->createStore();
		$this->createMission();	
	}
	
	private function clearTables() {
		DB::table('users')->delete();
		DB::table('combos')->delete();	
		DB::table('comboItems')->delete();
		DB::table('missions')->delete();	
		DB::table('items')->delete();	
		DB::table('orders')->delete();	
		DB::table('orderItems')->delete();
		DB::table('orderCombos')->delete();
		DB::table('stores')->delete();
	}
	
	private function createUser() {
		User::create(['name' => '管理員', 'serial' => 0, 'password' => Hash::make('admin')]);
		User::create(['name' => 'AAA', 'serial' => 'A', 'password' => Hash::make('admin')]);
	}
	
	private function createStore() {
		$store1 = Store::create(['name' => '東方美', 'phone' => 'xxxx-xxxx', 'address' => '地址...', 'detail' => '']);
		Store::create(['name' => '豪客', 'phone' => 'xxxx-xxxx', 'address' => '地址...', 'detail' => '']);
		Store::create(['name' => '麥味登', 'phone' => 'xxxx-xxxx', 'address' => '地址...', 'detail' => '']);
		
		$item1 = new Item(['name' => '火腿起司堡', 'price' => 30]);		
		$item2 = new Item(['name' => '烤三明治', 'price' => 30]);
		$item3 = new Item(['name' => '火腿', 'price' => 20]);
		
		$drink1 = new Item(['name' => '奶茶', 'price' => 20, 'isIndependent' => false]);
		$drink2 = new Item(['name' => '紅茶', 'price' => 15, 'isIndependent' => false]);
		
		$store1->items()->saveMany([$item1, $item2, $item3, $drink1, $drink2]);
		
		$item1->opts()->save($this->optFactory('加蛋', 5));
		$item2->opts()->save($this->optFactory('加蛋', 5));
		$drink1->opts()->saveMany([$this->optFactory('冰', 0), $this->optFactory('熱', 0), 
			$this->optFactory('小杯', 0), $this->optFactory('中杯', 5), $this->optFactory('大杯', 10)]);
		$drink2->opts()->saveMany([$this->optFactory('冰', 0), $this->optFactory('熱', 0), 
			$this->optFactory('小杯', 0), $this->optFactory('中杯', 5), $this->optFactory('大杯', 10)]);
				
		$combo1 = new Combo(['name' => 'A套餐', 'price' => 50]);
		$combo2 = new Combo(['name' => 'B套餐', 'price' => 55]);
		$store1->combos()->saveMany([$combo1, $combo2]);
		
		$comboItem1 = ComboItem::create(['combo_id' => $combo1->id, 'item_id' => $drink1->id]);
		CtrlUtil::setOpt($comboItem1, [$drink1->opts[0]->id, $drink1->opts[3]->id]);
		$comboItem2 = ComboItem::create(['combo_id' => $combo1->id, 'item_id' => $item1->id]);
		$comboItem3 = ComboItem::create(['combo_id' => $combo2->id, 'item_id' => $item2->id]);
		$comboItem4 = ComboItem::create(['combo_id' => $combo2->id, 'item_id' => $drink2->id]);
	}
	
	private function optFactory($name, $price) {
		return new Opt(['name' => $name, 'price' => $price]);
	}
	
	private function createMission() {
		$user1 = User::where('serial', 0)->first();	
		$user2 = User::where('serial', 'A')->first();
		$store1 = Store::where('name', '東方美')->first();
		$item1 = Item::where('name', '火腿起司堡')->first();
		$combo1 = Combo::where('name', 'A套餐')->first();
		
		$mission = Mission::create(['name' => '週四早餐', 'user_id' => $user1->id, 'store_id' => $store1->id]);		
		$order = Order::create(['user_id' => $user2->id, 'mission_id' => $mission->id]);
		$orderItem = OrderItem::create(['item_id' => $item1->id, 'order_id' => $order->id]);
		CtrlUtil::setOpt($orderItem, [$item1->opts[0]->id]);
		$orderCombo = OrderCombo::create(['combo_id' => $combo1->id, 'order_id' => $order->id]);
	}

}
