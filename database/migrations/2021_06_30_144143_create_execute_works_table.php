<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExecuteWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('execute_works', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('appointment_id')->nullable();
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('SET NULL');

            $table->uuid('work_id')->nullable();
            $table->foreign('work_id')
                ->references('id')
                ->on('works')
                ->onDelete('SET NULL');

            $table->boolean('is_finished')->nullable();
            $table->boolean('is_continuation')->default(false);
            $table->uuid('previous_execute_work_id')->nullable();

            $table->text('note')->nullable();

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
        Schema::dropIfExists('execute_works');
    }
}
