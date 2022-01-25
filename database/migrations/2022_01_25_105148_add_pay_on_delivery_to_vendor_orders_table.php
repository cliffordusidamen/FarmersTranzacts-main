<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayOnDeliveryToVendorOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_orders', function (Blueprint $table) {
            $table->boolean('pay_on_delivery')->default(false)->after('address_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('vendor_orders', function (Blueprint $table) {
            $table->dropColumn('pay_on_delivery');
        });
    }
}
