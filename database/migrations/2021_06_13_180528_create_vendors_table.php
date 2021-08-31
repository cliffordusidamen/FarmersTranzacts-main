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
            $table->integerIncrements('id');
            $table->string('shop_name')->unique();
            $table->string('business_name')->unique();
            $table->string('slug')->unique();
            $table->string('phone', 20)->nullable();
            $table->string('tin', 20)->unique()->nullable();
            $table->boolean('is_vat_registered')->default(false);
            $table->enum('ownership_type', Vendor::getOwnershipTypes());
            $table->string('logo_image_path', 2048)->nullable();
            $table->string('reject_reason', 255)->nullable();
            $table->unsignedSmallInteger('entity_country_id')->nullable();
            $table->enum('approval_status', Vendor::getApprovalStatuses())->default(Vendor::STATUS_PENDING);
            $table->timestamps();

            $table->foreign('entity_country_id')
                ->references('id')
                ->on('countries')
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('vendors');
        Schema::enableForeignKeyConstraints();
    }
}
