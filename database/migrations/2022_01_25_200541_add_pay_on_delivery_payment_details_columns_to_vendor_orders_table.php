<?php

use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayOnDeliveryPaymentDetailsColumnsToVendorOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('vendor_orders', function (Blueprint $table) {
            $table->enum('pay_on_delivery_method', Order::getPayOnDeliveryMethods(false))
                ->nullable()
                ->after('pay_on_delivery');

            $table->unsignedBigInteger('pay_on_delivery_bank_account_id')
                ->nullable()
                ->after('pay_on_delivery_method');

            $table->string('pay_on_delivery_reference_number', 255)
                ->nullable()
                ->after('pay_on_delivery_bank_account_id');

            $table->foreign('pay_on_delivery_bank_account_id')
                ->references('id')
                ->on('vendor_bank_accounts')
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
        Schema::table('vendor_orders', function (Blueprint $table) {
            $table->dropForeign(['pay_on_delivery_bank_account_id']);
            $table->dropColumn('pay_on_delivery_method');
            $table->dropColumn('pay_on_delivery_bank_account_id');
            $table->dropColumn('pay_on_delivery_reference_number');
        });
    }
}
