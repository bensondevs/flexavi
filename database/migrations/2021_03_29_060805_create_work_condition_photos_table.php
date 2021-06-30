<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkConditionPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_condition_photos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('uploader_id');
            $table->foreign('uploader_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            $table->uuid('work_id');
            $table->foreign('work_id')
                ->references('id')
                ->on('works')
                ->onDelete('CASCADE');

            $table->string('photo_type');
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
        Schema::dropIfExists('work_condition_photos');
    }
}
