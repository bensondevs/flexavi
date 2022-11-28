<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarrantyAppointmentWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warranty_appointment_works', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('warranty_appointment_id');
            $table->foreign('warranty_appointment_id')
                ->references('id')
                ->on('warranty_appointments')
                ->onDelete('CASCADE');

            $table->uuid('work_warranty_id');
            $table->foreign('work_warranty_id')
                ->references('id')
                ->on('work_warranties')
                ->onDelete('CASCADE');

            $table->double('company_paid');
            $table->double('customer_paid');

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
        Schema::dropIfExists('warranty_appointment_works');
    }
}
