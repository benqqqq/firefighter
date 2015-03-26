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
			$table->string('serial');
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
			$table->boolean('isIndependent')->default(true);
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
		
		Schema::create('comboItems', function($table) {
			$table->increments('id');
			$table->string('optStr')->default('');
			$table->integer('optPrice')->default(0);
			$table->integer('item_id')->unsigned();
			$table->integer('combo_id')->unsigned();
			
			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
			$table->foreign('combo_id')->references('id')->on('combos')->onDelete('cascade');			
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
			$table->timestamps();
			
			$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');	
			$table->foreign('mission_id')->references('id')->on('missions')->onDelete('cascade');	
		});

		Schema::create('orderItems', function($table) {
			$table->increments('id');			
			$table->string('optStr')->default('');
			$table->integer('optPrice')->default(0);
			$table->integer('quantity')->default(1);
			$table->integer('item_id')->unsigned();			
			$table->integer('order_id')->unsigned();
			
			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');
			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');			
		});
		
		Schema::create('orderCombos', function($table) {
			$table->increments('id');
			$table->integer('combo_id')->unsigned();			
			$table->integer('order_id')->unsigned();
			$table->integer('quantity')->default(1);
			
			$table->foreign('combo_id')->references('id')->on('combos')->onDelete('cascade');
			$table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade');			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{				
		Schema::drop('orderItems');
		Schema::drop('orderCombos');
		Schema::drop('orders');
		Schema::drop('missions');
		Schema::drop('users');
		Schema::drop('opts');
		Schema::drop('comboItems');
		Schema::drop('items');
		Schema::drop('combos');
		Schema::drop('stores');
		
	}

}
