<?php

use App\Models\Vendor;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->id();
            $table->boolean('is_individual')->default(false);
            $table->enum('approval_status', Vendor::getApprovalStatuses())->default(Vendor::STATUS_PENDING);
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('logo_image_path', 2048)->nullable();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('vendors');
        Schema::enableForeignKeyConstraints();
    }
}
