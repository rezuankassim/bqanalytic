<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyBqDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('bqdata');
        Schema::create('bqdata', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('event_date')->nullable();
            $table->string('event_name')->nullable();
            $table->string('currentPage')->nullable();
            $table->string('currentPage_value')->nullable();
            $table->string('subCategory')->nullable();
            $table->string('subCategory_value')->nullable();
            $table->string('user_id')->nullable();
            $table->string('user_pseudo_id')->nullable();
            $table->string('device_mobile_brand_name')->nullable();
            $table->string('device_mobile_model_name')->nullable();
            $table->string('device_mobile_marketing_name')->nullable();
            $table->string('device_operating_system_version')->nullable();
            $table->string('geo_city')->nullable();
            $table->string('geo_region')->nullable();
            $table->string('geo_country')->nullable();
            $table->string('geo_continent')->nullable();
            $table->string('geo_sub_continent')->nullable();
            $table->string('app_info_id')->nullable();
            $table->string('app_info_version')->nullable();
            $table->string('traffic_source_name')->nullable();
            $table->string('traffic_source_medium')->nullable();
            $table->string('traffic_source_source')->nullable();
            $table->string('platform')->nullable();
            $table->string('dataset');
            $table->index(['event_name', 'dataset', 'event_date']);
        });
    }
}
