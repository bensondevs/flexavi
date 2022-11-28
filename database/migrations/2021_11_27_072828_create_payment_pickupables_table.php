<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentPickupablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_pickupables', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('payment_pickup_id');
            $table->foreign('payment_pickup_id')
                ->references('id')
                ->on('payment_pickups')
                ->onDelete('CASCADE');

            $table->uuidMorphs('pickupable');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_pickupables');
    }
}
