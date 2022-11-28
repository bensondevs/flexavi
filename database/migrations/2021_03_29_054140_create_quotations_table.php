<?php

use App\Enums\Quotation\QuotationStatus;
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
        Schema::create('quotations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('SET NULL');

            $table->uuid('appointment_id')->nullable();
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('SET NULL');

            $table->uuid('customer_id')->nullable();
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('SET NULL');

            $table->string('number');
            $table->date('date')->nullable();
            $table->date('expiry_date')->nullable();
            $table->string('customer_address')->nullable();


            $table->json('taxes')->nullable();
            $table->double('amount', 8, 2)->default(0);
            $table->double('discount_amount', 8, 2)->default(0);
            $table->double('potential_amount', 8, 2)->default(0);
            $table->double('total_amount', 8, 2)->default(0);

            $table->integer('status')->default(QuotationStatus::Drafted);

            $table->text('note')->nullable();


            $table->datetime('nullified_at')->nullable();
            $table->datetime('sent_at')->nullable();
            $table->datetime('signed_at')->nullable();

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
        Schema::dropIfExists('quotations');
    }
};
