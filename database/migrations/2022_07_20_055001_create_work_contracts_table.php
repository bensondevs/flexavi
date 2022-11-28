<?php

use App\Enums\WorkContract\WorkContractStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkContractsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('work_contracts', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('CASCADE');

            $table->uuid('customer_id')->nullable();
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('CASCADE');

            $table->uuid('appointment_id')->nullable();
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('CASCADE');

            $table->string('footer')->nullable();

            $table->string('number')->unique();
            $table->double('amount')->default(0);
            $table->json('taxes')->nullable();
            $table->double('discount_amount')->default(0);
            $table->double('potential_amount')->default(0);
            $table->double('total_amount')->default(0);
            $table->tinyInteger('status')->default(WorkContractStatus::Drafted);
            $table->timestamps();
            $table->datetime('nullified_at')->nullable();
            $table->datetime('sent_at')->nullable();
            $table->datetime('signed_at')->nullable();
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
        Schema::dropIfExists('work_contracts');
    }
}
