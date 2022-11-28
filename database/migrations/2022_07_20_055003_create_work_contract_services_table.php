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
        Schema::create('work_contract_services', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('work_contract_id');
            $table->foreign('work_contract_id')
                ->references('id')
                ->on('work_contracts')
                ->onDelete('CASCADE');

            $table->uuid('work_service_id');
            $table->foreign('work_service_id')->references('id')
                ->on('work_services')
                ->onDelete('CASCADE');

            $table->integer('amount');
            $table->integer('tax_percentage')->default(0);
            $table->double('unit_price');
            $table->double('total');
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
        Schema::dropIfExists('work_contract_services');
    }
};
