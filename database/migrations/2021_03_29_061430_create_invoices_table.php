<?php

use App\Enums\Invoice\InvoicePaymentMethod;
use App\Enums\Invoice\InvoiceStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('number')->nullable();

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('SET NULL');

            $table->uuid('customer_id')->nullable();
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('CASCADE');

            $table->text('customer_address')->nullable();
            $table->date('date');
            $table->date('due_date')->nullable();


            $table->double('amount', 8, 2)->default(0);
            $table->json('taxes')->nullable();
            $table->double('discount_amount', 8, 2)->default(0);
            $table->double('total_amount', 8, 2)->default(0);
            $table->double('potential_amount', 8, 2)->default(0);

            $table->tinyInteger('status')->default(InvoiceStatus::Drafted);
            $table->char('payment_method')->default(InvoicePaymentMethod::Cash);
            $table->longText('note')->nullable();


            $table->timestamp('sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
}
