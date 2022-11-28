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
        Schema::create('work_contract_signatures', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('work_contract_id');
            $table->foreign('work_contract_id', 'work_contract_id')
                ->references('id')
                ->on('work_contracts')
                ->onDelete('CASCADE');
            $table->string('name');
            $table->tinyInteger('type');
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
        Schema::dropIfExists('work_contract_signatures');
    }
};
