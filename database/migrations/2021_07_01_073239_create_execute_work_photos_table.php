<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExecuteWorkPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('execute_work_photos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('execute_work_id');
            $table->foreign('execute_work_id')
                ->references('id')
                ->on('execute_works')
                ->onDelete('CASCADE');

            $table->tinyInteger('photo_condition_type')->default(1);
            $table->text('photo_path');
            $table->text('photo_description')->nullable();

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
        Schema::dropIfExists('execute_work_photos');
    }
}
