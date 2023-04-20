<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsSlotedToDeliveryTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('delivery_types', function (Blueprint $table) {
            $table->boolean('is_sloted')
                ->after('additional_amount')
                ->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('delivery_types', function (Blueprint $table) {
            $table->dropColumn('is_sloted');
        });
    }
}
