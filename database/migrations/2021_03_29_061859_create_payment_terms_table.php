<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\PaymentTerm\{
    PaymentTermPaymentMethod as PaymentMethod,
    PaymentTermStatus as Status
};

class CreatePaymentTermsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_terms', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('SET NULL');

            $table->uuid('invoice_id')->nullable();
            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices')
                ->onDelete('SET NULL');

            $table->char('term_name')->nullable();
            $table->tinyInteger('payment_method')->default(PaymentMethod::Cash);
            $table->double('amount', 10, 2);
            $table->tinyInteger('status')->default(Status::Unpaid);
            $table->date('due_date');

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
        Schema::dropIfExists('payment_terms');
    }
}
