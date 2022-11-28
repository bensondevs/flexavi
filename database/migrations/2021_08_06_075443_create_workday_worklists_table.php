<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkdayWorklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('workday_worklists', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->uuid('workday_id');
            $table->foreign('workday_id')
                ->references('id')
                ->on('workdays')
                ->onDelete('CASCADE');

            $table->uuid('worklist_id');
            $table->foreign('worklist_id')
                ->references('id')
                ->on('worklists')
                ->onDelete('CASCADE');

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
        Schema::dropIfExists('workday_worklists');
    }
}
