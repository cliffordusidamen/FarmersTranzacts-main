<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentSpendingVolumesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_spending_volumes', function (Blueprint $table) {
            $table->integerIncrements('id');
            $table->integer('minimum_amount');
            $table->integer('maximum_amount')->nullable();
            $table->float('commission_percentage')->unique();
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
        Schema::dropIfExists('agent_spending_volumes');
    }
}
