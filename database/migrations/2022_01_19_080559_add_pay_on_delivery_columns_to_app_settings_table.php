<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPayOnDeliveryColumnsToAppSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->boolean('pay_on_delivery_allowed')->default(false)->after('social_whatsapp');
            $table->unsignedInteger('pay_on_delivery_minimum')->nullable()->after('pay_on_delivery_allowed');
            $table->unsignedInteger('pay_on_delivery_maximum')->nullable()->after('pay_on_delivery_minimum');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('app_settings', function (Blueprint $table) {
            $table->dropColumn('pay_on_delivery_allowed');
            $table->dropColumn('pay_on_delivery_minimum');
            $table->dropColumn('pay_on_delivery_maximum');
        });
    }
}
