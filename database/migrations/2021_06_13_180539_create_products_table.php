<?php

use App\Models\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('category_id');
            $table->unsignedInteger('subcategory_id')->nullable();
            $table->unsignedInteger('vendor_id');
            $table->string('name')->unique();
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedDecimal('price');
            $table->unsignedDecimal('old_price')->nullable();
            $table->unsignedInteger('in_stock')->default(0);
            $table->string('disapproval_reason', 255)->nullable();
            $table->enum('approval_status', Product::getApprovalStatuses())->default(Product::STATUS_PENDING);
            $table->dateTime('submitted_at')->nullable();
            $table->dateTime('approved_at')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')
                ->references('id')
                ->on('vendors')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('category_id')
                ->references('id')
                ->on('product_categories')
                ->onUpdate('cascade')
                ->onDelete('cascade');

            $table->foreign('subcategory_id')
                ->references('id')
                ->on('product_subcategories')
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
        Schema::dropIfExists('products');
        Schema::enableForeignKeyConstraints();
    }
}
