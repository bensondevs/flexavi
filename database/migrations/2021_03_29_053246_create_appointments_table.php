<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('SET NULL');

            $table->uuid('customer_id')->nullable();
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('SET NULL');

            // Reschedule Information
            $table->uuid('previous_appointment_id')->nullable();
            $table->uuid('next_appointment_id')->nullable();

            $table->datetime('start');
            $table->datetime('end');
            $table->boolean('include_weekend')->default(0);

            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('type');

            $table->text('description')->nullable();
            $table->text('note')->nullable();

            // Reschedule cancel
            $table->string('cancellation_cause')->nullable();
            $table->tinyInteger('cancellation_vault')->nullable();
            $table->text('cancellation_note')->nullable();
            $table->json('cancellation_data')->nullable();

            // Time Frames
            $table->timestamps();
            $table->timestamp('in_process_at')->nullable();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('calculated_at')->nullable();
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
        Schema::dropIfExists('appointments');
    }
}
