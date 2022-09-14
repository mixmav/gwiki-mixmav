<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFingerprintIdColumnToMailingListSubscribersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('mailing_list_subscribers', function (Blueprint $table) {
            $table->integer('fingerprint_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('mailing_list_subscribers', function (Blueprint $table) {
            $table->dropColumn('fingerprint_id');
        });
    }
}
