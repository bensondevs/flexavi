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

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('SET NULL');

            $table->uuid('work_id')->nullable();
            $table->foreign('work_id')
                ->references('id')
                ->on('works')
                ->onDelete('SET NULL');

            $table->uuid('appointment_id')->nullable();
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('SET NULL');

            $table->uuid('sub_appointment_id')->nullable();
            $table->foreign('sub_appointment_id')
                ->references('id')
                ->on('sub_appointments')
                ->onDelete('SET NULL');

            $table->tinyInteger('status')->default(1);

            $table->string('description');
            $table->text('note')->nullable();
            $table->text('finish_note')->nullable();

            $table->timestamps();
            $table->timestamp('finished_at');
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
        Schema::dropIfExists('execute_works');
    }
}
