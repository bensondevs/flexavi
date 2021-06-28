<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('CASCADE');

            $table->uuid('customer_id')->nullable();
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('SET NULL');

            $table->date('contract_date_start');
            $table->date('contract_date_end');
            $table->boolean('include_weekend')->default(false);

            $table->double('amount', 10, 2);
            $table->char('payment_method');

            $table->char('status');

            $table->boolean('is_signed')->default(false);
            $table->json('content')->nullable();
            $table->text('pdf_url');

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
        Schema::dropIfExists('work_contracts');
    }
}
