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

            $table->uuid('company_id');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('CASCADE');

            $table->uuid('appointment_id')->nullable();
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('SET NULL');

            $table->uuid('previous_sub_appointment_id')->nullable();
            $table->uuid('rescheduled_sub_appointment_id')->nullable();

            $table->tinyInteger('status')->default(1);

            $table->datetime('start');
            $table->datetime('end');

            $table->string('cancellation_cause')->nullable();
            $table->tinyInteger('cancellation_vault')->nullable();
            $table->text('cancellation_note')->nullable();

            $table->text('note')->nullable();

            $table->timestamps();
            $table->timestamp('in_process_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
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
