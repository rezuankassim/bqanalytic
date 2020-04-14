<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBQAnalyticTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('big_query_data', function (Blueprint $table) {
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
        });

        Schema::create('analytics', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('analytic_user', function (Blueprint $table) {
            $table->unsignedBigInteger('analytic_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->foreign('analytic_id')->references('id')->on('analytics')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
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
        Schema::dropIfExists('big_queries');
        Schema::dropIfExists('analytics');
        Schema::dropIfExists('analytic_user');
    }
}
