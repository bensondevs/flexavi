<?php

use App\Enums\Currency;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPlanPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('subscription_plan_periods', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('subscription_plan_id');
            $table->foreign('subscription_plan_id')
                ->references('id')
                ->on('subscription_plans')
                ->onDelete('CASCADE');

            $table->string('name');
            $table->text('description')->nullable();
            $table->string('interval')->default('monthly');
            $table->double('amount', 8, 2);
            $table->string('currency')->default(Currency::EUR);
            $table->text('first_payment_description')->nullable();
            $table->double('first_payment_amount', 8, 2)->default(0.01);
            $table->string('first_payment_currency')->default(Currency::EUR);
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
        Schema::dropIfExists('subscription_plan_periods');
    }
}
