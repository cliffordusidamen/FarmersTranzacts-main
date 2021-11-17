<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agents', function (Blueprint $table) {
            $table->id();
            $table->string('business_name')->unique()->nullable();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('address_id');
            $table->unsignedInteger('spending_volume_id')->nullable();
            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('address_id')
                ->references('id')
                ->on('addresses')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('spending_volume_id')
                ->references('id')
                ->on('agent_spending_volumes')
                ->onDelete('set null')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agents');
    }
}
