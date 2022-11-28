<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefundItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('refund_items', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('refund_id');
            $table->unsignedBigInteger('original_order_item_id')->nullable();
            $table->string('owner_type');
            $table->uuid('owner_id');
            $table->string('description');
            $table->text('description_extra_lines')->nullable();
            $table->string('currency', 3);
            $table->unsignedInteger('quantity');
            $table->integer('unit_price');
            $table->decimal('tax_percentage', 6, 4);
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
        Schema::dropIfExists('refund_items');
    }
}
