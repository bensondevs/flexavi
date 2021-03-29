<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarrantyClaimsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warranty_claims', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('warranty_id');
            $table->foreign('warranty_id')
                ->references('id')
                ->on('warranties')
                ->onDelete('CASCADE');

            $table->string('claim_reason');
            $table->text('description');
            $table->char('claim_status');

            $table->uuid('appointment_id')
                ->nullable();
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('CASCADE');

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
        Schema::dropIfExists('warranty_claims');
    }
}
