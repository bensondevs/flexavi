<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationRevisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_revision', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('quotation_id');
            $table->foreign('quotation_id')
                ->references('id')
                ->on('quotations')
                ->onDelete('CASCADE');

            $table->uuid('revision_requester_id');
            $table->foreign('revision_requester_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            $table->boolean('is_applied')->default(false);
            $table->datetime('applied_at')->nullable();
            $table->json('revisions')->nullable();

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
        Schema::dropIfExists('quotation_revision');
    }
}
