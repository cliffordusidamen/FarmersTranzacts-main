<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayOnDeliveryColumnsToProductDeliveryCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('product_delivery_costs', function (Blueprint $table) {
            $table->boolean('north_central_pay_on_delivery')->default(false)->after('north_central_delivery_days');
            $table->boolean('north_east_pay_on_delivery')->default(false)->after('north_east_delivery_days');
            $table->boolean('north_west_pay_on_delivery')->default(false)->after('north_west_delivery_days');
            $table->boolean('south_east_pay_on_delivery')->default(false)->after('south_east_delivery_days');
            $table->boolean('south_west_pay_on_delivery')->default(false)->after('south_west_delivery_days');
            $table->boolean('south_south_pay_on_delivery')->default(false)->after('south_south_delivery_days');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('product_delivery_costs', function (Blueprint $table) {
            $table->dropColumn('north_central_pay_on_delivery');
            $table->dropColumn('north_east_pay_on_delivery');
            $table->dropColumn('north_west_pay_on_delivery');
            $table->dropColumn('south_east_pay_on_delivery');
            $table->dropColumn('south_west_pay_on_delivery');
            $table->dropColumn('south_south_pay_on_delivery');
        });
    }
}
