<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveHashColumnAndAddFingerprintIdColumnToPageViewsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('page_views', function (Blueprint $table) {
            $table->dropColumn('hash');
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
        Schema::table('page_views', function (Blueprint $table) {
            $table->string('hash')->nullable();
            $table->dropColumn('fingerprint_id');
        });
    }
}
