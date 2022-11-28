<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workables', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('work_id');
            $table->foreign('work_id')    
                ->references('id')
                ->on('works')
                ->onDelete('CASCADE');

            $table->uuidMorphs('workable');

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
        Schema::dropIfExists('workables');
    }
}
