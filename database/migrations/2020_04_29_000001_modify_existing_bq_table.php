<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyExistingBqTable extends Migration
{
    public function up()
    {
        Schema::dropIfExists('bq_tables');
        Schema::create('bqtables', function (Blueprint $table) {
            $table->string('table_date');
            $table->boolean('status')->default(0);
            $table->string('bqproject_name');
            $table->timestamps();
        });

        Schema::dropIfExists('bq_data');
        Schema::create('bqdata', function (Blueprint $table) {
            $table->string('event_date')->nullable();
            $table->bigInteger('event_timestamp')->nullable();
            $table->string('event_name')->nullable();
            $table->longText('event_params')->nullable();
            $table->bigInteger('event_previous_timestamp')->nullable();
            $table->double('event_value_in_usd')->nullable();
            $table->bigInteger('event_bundle_sequence_id')->nullable();
            $table->bigInteger('event_server_timestamp_offset')->nullable();
            $table->string('user_id')->nullable();
            $table->string('user_pseudo_id')->nullable();
            $table->longText('user_properties')->nullable();
            $table->bigInteger('user_first_touch_timestamp')->nullable();
            $table->longText('user_ltv')->nullable();
            $table->longText('device')->nullable();
            $table->longText('geo')->nullable();
            $table->longText('app_info')->nullable();
            $table->longText('traffic_source')->nullable();
            $table->string('stream_id')->nullable();
            $table->string('platform')->nullable();
            $table->longText('event_dimensions')->nullable();
            $table->longText('ecommerce')->nullable();
            $table->longText('items')->nullable();
            $table->string('dataset');
        });

        Schema::dropIfExists('analytics');
        Schema::create('bqanalytics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::dropIfExists('analytic_preferences');
        Schema::create('bqanalyticpreferences', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->unsignedBigInteger('bqanalytic_id');
            $table->foreign('bqanalytic_id')->references('id')->on('bqanalytics')->onDelete('cascade');
            $table->unsignedBigInteger('bqapp_id')->nullable();
            $table->foreign('bqapp_id')->references('id')->on('bqapps')->onDelete('cascade');
        });
    }
}
