<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarrantiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warranties', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('work_id');
            $table->foreign('work_id')
                ->references('id')
                ->on('works')
                ->onDelete('CASCADE');

            $table->date('warranty_due');
            $table->text('internal_note')->nullable();
            $table->text('customer_note')->nullable();

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
        Schema::dropIfExists('warranties');
    }
}
