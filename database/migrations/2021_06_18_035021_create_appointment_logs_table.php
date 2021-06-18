<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointment_logs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('appointment_id');
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('CASCADE');

            $table->integer('day')->default(1);
            $table->date('date');
            $table->datetime('start');
            $table->datetime('end');

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
        Schema::dropIfExists('appointment_logs');
    }
}
