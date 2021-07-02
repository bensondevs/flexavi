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

            $table->uuid('quotation_id')->nullable();
            $table->foreign('quotation_id')
                ->references('id')
                ->on('quotations')
                ->onDelete('SET NULL');

            $table->uuid('work_contract_id')->nullable();
            $table->foreign('work_contract_id')
                ->references('id')
                ->on('work_contracts')
                ->onDelete('SET NULL');

            $table->uuid('appointment_id')->nullable();
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('SET NULL');

            $table->tinyInteger('status')->default(1);

            $table->integer('quantity');
            $table->string('quantity_unit');
            $table->text('description');
            $table->double('unit_price', 10, 2);
            $table->boolean('include_tax')->default(true);
            $table->integer('tax_percentage')->default(0);
            $table->double('total_price')->default(0);

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
