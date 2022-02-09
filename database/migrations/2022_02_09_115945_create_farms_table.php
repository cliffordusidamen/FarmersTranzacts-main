<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFarmsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('farms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('business_name', 255)->nullable();
            $table->unsignedMediumInteger('state_id')->nullable();
            $table->string('address_line_1', 255);
            $table->string('address_line_2', 255)->nullable();
            $table->string('city', 50)->nullable();
            $table->string('postal_code', 25)->nullable();
            $table->unsignedInteger('number_of_farmers')->nullable();
            $table->string('farm_size', 500)->nullable();
            $table->string('crops', 500)->nullable();
            $table->string('animals', 500)->nullable();
            $table->string('seed_demand', 500)->nullable();
            $table->string('fertilizer_demand', 500)->nullable();
            $table->string('agro_chemical_demand', 500)->nullable();
            $table->string('animal_feed_demand', 500)->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('set null')
                ->onUpdate('cascade');

            $table->foreign('state_id')
                ->references('id')
                ->on('states')
                ->onUpdate('cascade')
                ->onDelete('set null');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('farms');
    }
}
