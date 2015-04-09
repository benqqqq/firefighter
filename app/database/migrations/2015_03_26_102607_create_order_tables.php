<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTables extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		
		Schema::create('users', function($table) {
			$table->increments('id');
			$table->string('name');
			$table->string('serial')->unique();
			$table->string('password');
			
			$table->rememberToken();
		});
		
		Schema::create('stores', function($table) {
			$table->increments('id');
			$table->string('name');
			$table->string('phone');
			$table->string('address');
			$table->text('detail');
		});
		
		Schema::create('items', function($table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('price')->unsigned();
			$table->string('optStr')->default('');
			$table->integer('optPrice')->default(0);
						
			$table->integer('store_id')->unsigned();
			
			$table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');			
		});
		
		Schema::create('opts', function($table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('price');
			$table->integer('item_id')->unsigned();
			
			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');			
		});
		
		Schema::create('combos', function($table) {
			$table->increments('id');
			$table->string('name');
			$table->integer('price');
			$table->integer('store_id')->unsigned();
			
			$table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');			
		});
		
		Schema::create('combo_item', function($table) {
			$table->increments('id');
			$table->integer('combo_id')->unsigned();
			$table->integer('item_id')->unsigned();			
			$table->string('optStr')->default('');
			$table->integer('optPrice')->default(0);
						
			$table->foreign('combo_id')->references('id')->on('combos')->onDelete('cascade');
			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');						
		});	
		
		
		Schema::create('missions', function($table) {
			$table->increments('id');
			$table->string('name');
			$table->boolean('isEnding')->default(false);
			$table->integer('user_id')->unsigned();			
			$table->integer('store_id')->unsigned();			

			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');	
			$table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');	
			$table->timestamps();
		});
		
		Schema::create('orders', function($table) {
			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->integer('mission_id')->unsigned();
			$table->integer('paid')->default(0);
			$table->text('remark');
			
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');	
			$table->foreign('mission_id')->references('id')->on('missions')->onDelete('cascade');	
			$table->timestamps();
		});

		Schema::create('item_order', function($table) {
			$table->increments('id');
			$table->integer('order_id')->unsigned();
			$table->integer('item_id')->unsigned();			
			$table->string('optStr')->default('');
			$table->integer('optPrice')->default(0);
			$table->integer('quantity')->default(0);
						
			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');
			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');						
		});	

		
		Schema::create('ordercombos', function($table) {
			$table->increments('id');
			$table->integer('combo_id')->unsigned();			
			$table->integer('order_id')->unsigned();
			$table->integer('quantity')->default(0);
			$table->string('optStr')->default('');
			$table->integer('optPrice')->default(0);
			
			$table->foreign('combo_id')->references('id')->on('combos')->onDelete('cascade');
			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');			
		});
		
		Schema::create('item_ordercombo', function($table) {
			$table->increments('id');
			$table->integer('ordercombo_id')->unsigned();
			$table->integer('item_id')->unsigned();			
			$table->string('optStr')->default('');
			$table->integer('optPrice')->default(0);
						
			$table->foreign('ordercombo_id')->references('id')->on('ordercombos')->onDelete('cascade');
			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');						
		});	
	
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{				
		Schema::drop('item_ordercombo');
		Schema::drop('ordercombos');
		Schema::drop('item_order');
		Schema::drop('orders');
		Schema::drop('missions');
		Schema::drop('users');
		Schema::drop('opts');
		Schema::drop('combo_item');
		Schema::drop('items');
		Schema::drop('combos');
		Schema::drop('stores');		
	}

}
