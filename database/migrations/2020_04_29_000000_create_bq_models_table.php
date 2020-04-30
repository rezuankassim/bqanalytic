<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBQModelsTable extends Migration
{
    public function up()
    {
        Schema::create('bqprojects', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('google_project_id');
            $table->string('google_bq_dataset_name');
            $table->string('google_credential_path');
            $table->string('google_credential_file_name');
            $table->timestamps();
        });

        Schema::create('bqclients', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('bqapps', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('bundles');
            $table->unsignedBigInteger('bqclient_id');
            $table->foreign('bqclient_id')->references('id')->on('bqclients')->onDelete('cascade');
            $table->unsignedBigInteger('bqproject_id');
            $table->foreign('bqproject_id')->references('id')->on('bqprojects')->onDelete('cascade');
            $table->timestamps();
        });
        
    }
}
