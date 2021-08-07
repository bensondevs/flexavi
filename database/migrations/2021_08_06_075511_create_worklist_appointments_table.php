<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorklistAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worklist_appointments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->uuid('worklist_id');
            $table->foreign('worklist_id')
                ->references('id')
                ->on('worklists')
                ->onDelete('CASCADE');

            $table->uuid('appointment_id');
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('CASCADE');

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
        Schema::dropIfExists('worklist_appointments');
    }
}
