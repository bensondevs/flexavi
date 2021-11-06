<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarRegisterEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_register_employees', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('CASCADE');

            $table->uuid('car_id');
            $table->foreign('car_id')
                ->references('id')
                ->on('cars')
                ->onDelete('CASCADE');

            $table->uuid('car_register_time_id')->nullable();
            $table->foreign('car_register_time_id')
                ->references('id')
                ->on('car_register_times')
                ->onDelete('CASCADE');

            $table->uuid('employee_id');
            $table->foreign('employee_id')
                ->references('id')
                ->on('employees')
                ->onDelete('CASCADE');

            $table->tinyInteger('passanger_type')->default(1);

            $table->datetime('out_time')->nullable();
            $table->datetime('in_time')->nullable();

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
        Schema::dropIfExists('car_register_employees');
    }
}
