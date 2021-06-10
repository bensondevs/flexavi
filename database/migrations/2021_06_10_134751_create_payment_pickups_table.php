<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentPickupsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_pickups', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('appointment_id');
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('CASCADE');
            $table->uuid('picker_id')->nullable();
            $table->foreign('picker_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            $table->integer('pickup_amount')->default(0);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_pickups');
    }
}
