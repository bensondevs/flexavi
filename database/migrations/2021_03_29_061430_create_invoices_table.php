<?php

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
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('invoice_number')->nullable();

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('SET NULL');

            $table->uuid('customer_id');
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('CASCADE');

            $table->nullableUuidMorphs('invoiceable');

            $table->double('total', 10, 2)->default(0);
            $table->double('total_in_terms')->default(0);
            $table->double('total_paid')->default(0);

            $table->datetime('overdue_at')->nullable();
            $table->datetime('first_reminder_overdue_at')->nullable();
            $table->datetime('second_reminder_overdue_at')->nullable();
            $table->datetime('third_reminder_overdue_at')->nullable();
            $table->datetime('overdue_debt_collector_at')->nullable();

            $table->tinyInteger('status')->default(1);
            $table->char('payment_method')->default(1);

            $table->timestamps();
            $table->softDeletes();
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->timestamp('payment_overdue_at')->nullable();
            $table->timestamp('first_remider_sent_at')->nullable();
            $table->timestamp('second_reminder_sent_at')->nullable();
            $table->timestamp('third_reminder_sent_at')->nullable();
            $table->timestamp('debt_collector_sent_at')->nullable();
            $table->timestamp('paid_via_debt_collector_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
