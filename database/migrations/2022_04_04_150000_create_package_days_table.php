<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePackageDaysTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     *
     */
    public function up()
    {
        Schema::create('package_days', function (Blueprint $table) {
            $table->id();
            $table->integer('package_id')->unsigned();
            $table->integer('day');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('package_id')->references('id')->on('subscription_packages');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('package_days');
    }
}
