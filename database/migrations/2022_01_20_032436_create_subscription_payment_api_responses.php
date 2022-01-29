<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubscriptionPaymentApiResponses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscription_payment_api_responses', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('payment_id');
            $table->foreign('payment_id')
                ->references('id')
                ->on('subscription_payments')
                ->onDelete('CASCADE');
            $table->json('api_response')->nullable();

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
        Schema::dropIfExists('subscription_payment_api_responses');
    }
}
