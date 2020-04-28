<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAnalyticPreferencesTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('analytic_user');

        Schema::create('analytic_preferences', function (Blueprint $table) {
            $table->unsignedBigInteger('analytic_id');
            $table->unsignedBigInteger('user_id');
            $table->morphs('filterable');

            $table->foreign('analytic_id')->references('id')->on('analytics')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }
}