<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('appointment_id');
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('CASCADE');

            $table->uuid('previous_sub_appointment_id')->nullable();
            $table->uuid('rescheduled_sub_appointment_id')->nullable();

            $table->string('status');

            $table->datetime('start');
            $table->datetime('end');

            $table->string('cancellation_cause')->nullable();
            $table->string('cancellation_vault')->nullable();
            $table->text('cancellation_note')->nullable();

            $table->text('note')->nullable();

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
        Schema::dropIfExists('sub_appointments');
    }
}
