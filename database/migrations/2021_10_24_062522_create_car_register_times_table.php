<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarRegisterTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('car_register_times', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('CASCADE');

            $table->uuid('worklist_id')->nullable();
            $table->foreign('worklist_id')
                ->references('id')
                ->on('worklists')
                ->onDelete('CASCADE');

            $table->uuid('car_id');
            $table->foreign('car_id')
                ->references('id')
                ->on('cars')
                ->onDelete('CASCADE');

            $table->datetime('should_out_at');
            $table->datetime('should_return_at');

            $table->datetime('marked_out_at')->nullable();
            $table->datetime('marked_return_at')->nullable();

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
        Schema::dropIfExists('car_register_times');
    }
}
