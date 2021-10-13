<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table) {
            $table->engine = "InnoDB";
            $table->mediumIncrements('id');
            $table->unsignedSmallInteger('geopolitical_zone_id');
            $table->string('state_name', '25');
            $table->string('state_code', '10')->nullable();
            $table->string('capital', '25')->nullable();
            $table->timestamps();

            $table->foreign('geopolitical_zone_id')
                ->references('id')
                ->on('geopolitical_zones')
                ->onUpdate('cascade')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('states');
        Schema::enableForeignKeyConstraints();
    }
}
