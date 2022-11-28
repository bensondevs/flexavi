<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInspectionPicturesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inspection_pictures', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('name');
            $table->uuid('inspection_id')->nullable();
            $table->foreign('inspection_id')
                ->references('id')
                ->on('inspections')
                ->onDelete('SET NULL');

            $table->decimal('length', 8, 2);
            $table->decimal('width', 8, 2);
            $table->integer('amount');
            $table->longText('note')->nullable();

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
        Schema::dropIfExists('inspection_pictures');
    }
}
