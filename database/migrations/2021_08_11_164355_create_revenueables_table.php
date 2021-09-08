<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevenueablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revenueables', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('revenue_id');
            $table->foreign('revenue_id')
                ->references('id')
                ->on('revenues')
                ->onDelete('CASCADE');

            $table->uuid('revenueable_id');
            $table->string('revenueable_type');

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
        Schema::dropIfExists('revenueables');
    }
}
