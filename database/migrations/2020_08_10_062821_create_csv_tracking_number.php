<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCsvTrackingNumber extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('csv_tracking_number', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('tracking_number');
            $table->unsignedBigInteger('csv_shipping_id');
            $table->foreign('csv_shipping_id')
                ->references('id')
                ->on('csv_shipping')
                ->onUpdate('restrict')
                ->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('Models/CSVTrackingNumber');
    }
}
