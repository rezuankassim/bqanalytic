<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLastImportedAtForBqprojectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bqprojects', function (Blueprint $table) {
            $table->date('last_imported_date')->after('start_date')->nullable();
        });
    }
}
