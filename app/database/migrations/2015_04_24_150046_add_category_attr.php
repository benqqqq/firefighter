<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCategoryAttr extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('categories', function($table) {
			$table->integer('store_id')->unsigned();
			
			$table->foreign('store_id')->references('id')->on('stores')->onDelete('cascade');			
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('categories', function($table) {
			$table->dropColumn('store_id');
		});
	}

}
