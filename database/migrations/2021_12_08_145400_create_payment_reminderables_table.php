<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentReminderablesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payment_reminderables', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('payment_reminder_id');
            $table->foreign('payment_reminder_id')
                ->references('id')
                ->on('payment_reminders')
                ->onDelete('CASCADE');

            $table->uuidMorphs('reminderable');

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
        Schema::dropIfExists('payment_reminderables');
    }
}
