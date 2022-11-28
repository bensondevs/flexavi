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
        Schema::create('quotation_items', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('quotation_id');
            $table->foreign('quotation_id')
                ->references('id')
                ->on('quotations')
                ->onDelete('CASCADE');

            $table->uuid('work_service_id')->nullable();
            $table->foreign('work_service_id')
                ->references('id')
                ->on('work_services')
                ->onDelete('SET NULL');

            $table->integer('tax_percentage')->default(0);
            $table->double('unit_price', 8, 2);
            $table->integer('amount');
            $table->double('total', 8, 2);
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
        Schema::dropIfExists('quotation_items');
    }
};
