<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_payments', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL');

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('SET NULL');

            $table->uuid('subscription_id')->nullable();
            $table->foreign('subscription_id')
                ->references('id')
                ->on('subscriptions')
                ->onDelete('SET NULL');

            $table->uuid('pricing_id')->nullable();
            $table->foreign('pricing_id')
                ->references('id')
                ->on('pricings')
                ->onDelete('SET NULL');

            $table->char('payment_method');
            $table->json('bank_information_json');
            $table->double('paid_amount');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscription_payments');
    }
}
