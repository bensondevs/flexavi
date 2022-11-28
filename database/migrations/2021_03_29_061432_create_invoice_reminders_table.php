<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('invoice_reminders', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('invoice_id')->unique();
            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices')
                ->onDelete('CASCADE');

            $table->date('first_reminder_at')->nullable();
            $table->dateTime('user_first_reminder_sent_at')->nullable();
            $table->dateTime('customer_first_reminder_sent_at')->nullable();

            $table->date('second_reminder_at')->nullable();
            $table->dateTime('user_second_reminder_sent_at')->nullable();
            $table->dateTime('customer_second_reminder_sent_at')->nullable();

            $table->date('third_reminder_at')->nullable();
            $table->dateTime('user_third_reminder_sent_at')->nullable();
            $table->dateTime('customer_third_reminder_sent_at')->nullable();

            $table->date('sent_to_debt_collector_at')->nullable();
            $table->dateTime('user_sent_to_debt_collector_sent_at')->nullable();
            $table->dateTime('customer_sent_to_debt_collector_sent_at')->nullable();

            $table->dateTime('paid_via_debt_collector_at')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('invoice_reminders');
    }
};
