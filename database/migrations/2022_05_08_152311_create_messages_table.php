<?php

use App\Models\Message;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedMediumInteger('designation_id')->nullable();
            $table->string('channel')->default(Message::CHANNEL_EMAIL)->nullable();
            $table->string('subject')->nullable();
            $table->text('body')->nullable();
            $table->boolean('is_draft')->default(true);
            $table->timestamps();

            $table->foreign('designation_id')
                ->references('id')
                ->on('designations')
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
        Schema::dropIfExists('messages');
    }
}
