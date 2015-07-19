<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddApiToken extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//add column api_token
		Schema::table('ta_auth_tokens', function(Blueprint $table) {

        	$table->string('api_token',96)->nullable()->after('private_key');
    	});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		// drop column api_token
		Schema::table('ta_auth_tokens', function(Blueprint $table) {

        	$table->dropColumn('api_token');
    	});
	}

}
