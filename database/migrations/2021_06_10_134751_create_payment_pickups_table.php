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

            $table->uuid('employee_id')->nullable();
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('CASCADE');

            $table->uuid('pickup_for_appointment_id');
            $table->foreign('pickup_for_appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('CASCADE');

            $table->double('should_pickup_amount')->default(0); // should be picked up amount
            $table->double('picked_up_amount')->default(0);
            $table->text('reason_not_all')->nullable();
            
            $table->timestamp('should_picked_up_at');
            $table->timestamps('picked_up_at')->nullable();
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
        Schema::dropIfExists('payment_pickups');
    }
}
