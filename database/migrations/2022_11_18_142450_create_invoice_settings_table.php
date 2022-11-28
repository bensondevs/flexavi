<?php

use App\Enums\Invoice\InvoiceReminderSentType;
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
        Schema::create('invoice_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('invoice_id');
            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices')
                ->onDelete('cascade');

            $table->boolean('auto_reminder_activated')->default(false);

            $table->tinyInteger('first_reminder_type')->default(InvoiceReminderSentType::InHouseUser);
            $table->tinyInteger('first_reminder_days')->default(0);

            $table->tinyInteger('second_reminder_type')->default(InvoiceReminderSentType::InHouseUser);
            $table->integer('second_reminder_days')->default(3);

            $table->tinyInteger('third_reminder_type')->default(InvoiceReminderSentType::InHouseUser);
            $table->integer('third_reminder_days')->default(3);

            $table->tinyInteger('debt_collector_reminder_type')->default(InvoiceReminderSentType::InHouseUser);
            $table->integer('debt_collector_reminder_days')->default(3);

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
        Schema::dropIfExists('invoice_settings');
    }
};
