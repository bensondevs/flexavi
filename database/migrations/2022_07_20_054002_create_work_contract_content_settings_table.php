<?php

use App\Enums\Setting\WorkContract\WorkContractContentPositionType;
use App\Enums\Setting\WorkContract\WorkContractContentTextType;
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
        Schema::create('work_contract_content_settings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('work_contract_setting_id');
            $table->foreign('work_contract_setting_id')
                ->references('id')
                ->on('work_contract_settings')
                ->onDelete('CASCADE');

            $table->integer('order_index')->default(1);
            $table->tinyInteger('position_type')->default(WorkContractContentPositionType::Foreword);
            $table->tinyInteger('text_type')->default(WorkContractContentTextType::Title);
            $table->text('text');
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
        Schema::dropIfExists('work_contract_content_settings');
    }
};
