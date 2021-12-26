<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('hotline', 20)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('address', 200)->nullable();
            $table->unsignedSmallInteger('vendor_storage_limit')->nullable();
            $table->unsignedSmallInteger('cancel_order_hours')->nullable();
            $table->string('reference_number_prefix', 10)->nullable();
            $table->string('social_facebook', 500)->nullable();
            $table->string('social_twitter', 500)->nullable();
            $table->string('social_youtube', 500)->nullable();
            $table->string('social_linkedin', 500)->nullable();
            $table->string('social_instagram', 500)->nullable();
            $table->string('social_whatsapp', 500)->nullable();
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
        Schema::dropIfExists('app_settings');
    }
}
