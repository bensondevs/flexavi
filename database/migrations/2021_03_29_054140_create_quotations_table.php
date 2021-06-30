<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('SET NULL');

            $table->uuid('customer_id')->nullable();
            $table->foreign('customer_id')
                ->references('id')
                ->on('customers')
                ->onDelete('SET NULL');

            $table->uuid('appointment_id')->nullable();
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('SET NULL');

            $table->tinyInteger('type');
            $table->date('quotation_date');
            $table->string('quotation_number')->unique();

            $table->string('contact_person');

            $table->string('address');
            $table->string('zip_code');
            $table->string('phone_number')->nullable();

            $table->json('damage_causes');
            $table->text('quotation_description');

            $table->integer('amount')->default(0);
            $table->integer('vat_percentage')->default(0);
            $table->integer('discount_amount')->default(0);
            $table->integer('total_amount')->default(0);

            $table->date('expiry_date')->nullable();
            $table->integer('status')->default(1);

            $table->tinyInteger('payment_method')->default(1);

            $table->string('honor_note')->nullable();

            $table->tinyInteger('canceller')->nullable();
            $table->text('cancellation_reason')->nullable();

            $table->timestamps();
            $table->datetime('honored_at')->nullable();
            $table->datetime('first_sent_at')->nullable();
            $table->datetime('last_sent_at')->nullable();
            $table->datetime('revised_at')->nullable();
            $table->datetime('cancelled_at')->nullable();
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
        Schema::dropIfExists('quotations');
    }
}
