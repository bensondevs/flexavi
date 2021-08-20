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

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('SET NULL');

            /*$table->uuid('appointment_id')->nullable();
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('SET NULL');

            $table->uuid('quotation_id')->nullable();
            $table->foreign('quotation_id')
                ->references('id')
                ->on('quotations')
                ->onDelete('SET NULL');

            $table->uuid('work_contract_id')->nullable();
            $table->foreign('work_contract_id')
                ->references('id')
                ->on('work_contracts')
                ->onDelete('SET NULL');*/

            // $table->nullableUuidMorphs('workable');

            $table->tinyInteger('status')->default(1);

            $table->double('quantity', 8, 2)->default(0);
            $table->string('quantity_unit')->nullable();
            $table->text('description')->nullable();
            $table->double('unit_price', 10, 2);
            $table->boolean('include_tax')->default(true);
            $table->double('tax_percentage', 8, 2)->default(0);
            $table->double('total_price')->default(0);
            $table->double('total_paid')->default(0);

            // Notes
            $table->text('note')->nullable();
            $table->text('unfinish_note')->nullable();
            $table->text('finish_note')->nullable();

            $table->timestamps();
            $table->timestamp('executed_at')->nullable();
            $table->timestamp('finished_at')->nullable();
            $table->timestamp('marked_unfinished_at')->nullable();
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
