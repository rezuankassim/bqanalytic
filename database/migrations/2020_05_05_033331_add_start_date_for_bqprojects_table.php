<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStartDateForBqprojectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bqprojects', function (Blueprint $table) {
            $table->date('start_date')->after('google_credential_file_name')->nullable();
        });
    }
}
