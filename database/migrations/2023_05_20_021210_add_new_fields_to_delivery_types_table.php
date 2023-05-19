<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNewFieldsToDeliveryTypesTable extends Migration
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

            $table->time('start_at')
                ->after('is_sloted')
                ->nullable();

            $table->time('end_at')
                ->after('start_at')
                ->nullable();
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
            $table->dropColumn('start_at');
            $table->dropColumn('end_at');
        });
    }
}
