<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('SET NULL');

            $table->uuid('creator_id');
            $table->foreign('creator_id')
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL');

            $table->uuid('customer_id');
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('SET NULL');

            $table->uuid('appointment_id')->nullable();
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('SET NULL');

            $table->uuid('quotable_id')->nullable();
            $table->string('quotable_type')->nullable();

            $table->string('subject');
            $table->string('quotation_number');
            $table->string('quotation_type');
            $table->text('quotation_description');
            $table->text('quotation_document_url');

            $table->date('expiry_date');
            $table->char('status');

            $table->char('payment_method');

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
        Schema::dropIfExists('quotations');
    }
}
