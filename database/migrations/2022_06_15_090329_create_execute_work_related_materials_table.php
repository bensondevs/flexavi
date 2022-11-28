<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExecuteWorkRelatedMaterialsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('execute_work_related_materials', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('execute_work_id')->nullable();
            $table->foreign('execute_work_id')
                ->references('id')
                ->on('execute_works')
                ->onDelete('CASCADE');

            $table->boolean('related_quotation')->default(false);
            $table->boolean('related_work_contract')->default(false);
            $table->boolean('related_invoice')->default(false);

            $table->uuid('quotation_id')->nullable();
            $table->foreign('quotation_id')
                ->references('id')
                ->on('quotations')
                ->onDelete('SET NULL');

            $table->uuid('invoice_id')->nullable();
            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices')
                ->onDelete('SET NULL');

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
        Schema::dropIfExists('execute_work_related_materials');
    }
}
