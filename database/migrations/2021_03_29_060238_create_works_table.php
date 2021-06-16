<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('works', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('work_contract_id');
            $table->foreign('work_contract_id')
                ->references('id')
                ->on('work_contracts')
                ->onDelete('CASCADE');

            $table->uuid('appointment_id')->nullable();
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('SET NULL');

            $table->string('name');
            $table->text('description');
            $table->double('price', 10, 2);

            $table->boolean('include_tax')->default(true);
            $table->double('tax', 10, 2);

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
        Schema::dropIfExists('works');
    }
}
