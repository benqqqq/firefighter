<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EditStoreInfo extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('photos', function($table) {
			$table->increments('id');
			$table->string('src');
			$table->integer('store_id')->unsigned();
			
			$table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');			
		});
		
		Schema::table('missions', function($table) {
			$table->datetime('deadline');
			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('photos');
		Schema::table('missions', function($table) {
			$table->dropColumn('deadline');			
		});
	}
}
