<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHelpPagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('help_pages', function (Blueprint $table) {
            $table->id();
            $table->string('slug')->unique();
            $table->string('title', 500)->nullable();
            $table->string('description', 500)->nullable();
            $table->mediumText('content')->nullable();
            $table->boolean('published')->default(false);
            $table->string('icon_path', 2048)->nullable();
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
        Schema::dropIfExists('help_pages');
    }
}
