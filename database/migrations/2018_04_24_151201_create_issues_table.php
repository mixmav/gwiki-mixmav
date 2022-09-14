<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIssuesTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('issues', function (Blueprint $table) {
			$table->increments('id');

			$table->string('name', 100)->nullable();
			$table->string('email')->nullable();
			
			$table->string('link', 100)->nullable();
            $table->string('message', 2000);

			$table->ipAddress('IP');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('issues');
	}
}
