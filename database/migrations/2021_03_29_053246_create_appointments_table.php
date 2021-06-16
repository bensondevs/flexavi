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

            $table->uuid('company_id');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('CASCADE');

            $table->uuid('customer_id');
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('SET NULL');

            // Reschedule cancel
            $table->boolean('canceled')->default(false);
            $table->uuid('reschedule_appointment_id')->nullable();

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
