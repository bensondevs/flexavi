<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorklistCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worklist_cars', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('worklist_id');
            $table
                ->foreign('worklist_id')
                ->references('id')
                ->on('worklists')
                ->onDelete('cascade');
            $table->uuid('car_id');
            $table
                ->foreign('car_id')
                ->references('id')
                ->on('cars')
                ->onDelete('cascade');
            $table->uuid('employee_in_charge_id')->nullable();
            $table
                ->foreign('employee_in_charge_id')
                ->references('id')
                ->on('employees')
                ->onDelete('SET NULL');
            $table->datetime('should_return_at')->nullable();
            $table->datetime('returned_at')->nullable();
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
        Schema::dropIfExists('worklist_cars');
    }
}
