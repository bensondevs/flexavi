<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentPickupItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_pickup_items', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('payment_pickup_id');
            $table->foreign('payment_pickup_id')
                ->references('id')
                ->on('payment_pickups')
                ->onDelete('CASCADE');

            $table->json('payment_term_ids')->nullable();

            $table->uuid('invoice_id');
            $table->foreign('invoice_id')
                ->references('id')
                ->on('invoices')
                ->onDelete('CASCADE');

            $table->double('total_bill', 8, 2)->default(0);

            $table->double('pickup_amount', 8, 2)->default(0);

            $table->longText('note')->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payment_pickup_items');
    }
}
