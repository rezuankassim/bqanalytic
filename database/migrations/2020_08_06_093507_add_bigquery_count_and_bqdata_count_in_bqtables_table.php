<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddBigqueryCountAndBqdataCountInBqtablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bqtables', function (Blueprint $table) {
            // $table->id('id');
            $table->integer('bigquery_count')->nullable()->after('bqproject_name');
            $table->integer('bqdata_count')->nullable()->after('bigquery_count');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bqtables', function (Blueprint $table) {
            $table->dropColumn(['id', 'bigquery_count', 'bqdata_count']);
        });
    }
}
