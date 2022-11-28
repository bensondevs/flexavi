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

            $table->string('name');
            $table->decimal('length', 8, 2);
            $table->longText('note')->nullable();

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
