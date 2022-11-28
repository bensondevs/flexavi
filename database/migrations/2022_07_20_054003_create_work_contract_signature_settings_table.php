<?php

use App\Enums\Setting\WorkContract\WorkContractSignatureType;
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
        Schema::create('work_contract_signature_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('work_contract_setting_id');
            $table->foreign('work_contract_setting_id', 'work_contract_setting_id')
                ->references('id')
                ->on('work_contract_settings')
                ->onDelete('CASCADE');

            $table->string('name');
            $table->tinyInteger('type')->default(WorkContractSignatureType::Roofer);
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
        Schema::dropIfExists('work_contract_signature_settings');
    }
};
