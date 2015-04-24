<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCategoryTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('categories', function($table) {
			$table->increments('id');
			$table->string('name');				
		});

		Schema::create('category_item', function($table) {
			$table->increments('id');
			$table->integer('category_id')->unsigned();
			$table->integer('item_id')->unsigned();						
						
			$table->foreign('category_id')->references('id')->on('categories')->onDelete('cascade');
			$table->foreign('item_id')->references('id')->on('items')->onDelete('cascade');						
		});

		Schema::table('items', function($table) {
			$table->string('remark')->default('');
		});

		Schema::table('combos', function($table) {
			$table->string('remark')->default('');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('categories');
		Schema::drop('category_item');
		Schema::table('items', function($table) {
			$table->dropColumn('remark');
		});
		Schema::table('combos', function($table) {
			$table->dropColumn('remark');
		});
	}

}
