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

            // Reschedule cancel
            $table->boolean('is_late')->default(false);
            $table->boolean('cancelled')->default(false);
            $table->text('cancellation_cause')->nullable();

            $table->datetime('start');
            $table->datetime('end');
            $table->boolean('include_weekend');

            $table->string('appointment_status');
            $table->string('appointment_type');

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
        Schema::dropIfExists('appointments');
    }
}
