<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateQuotationPhotosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('quotation_photos', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('quotation_id');
            $table->foreign('quotation_id')
                ->references('id')
                ->on('quotations')
                ->onDelete('CASCADE');

            $table->text('photo_url');
            $table->text('photo_description');

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
        Schema::dropIfExists('quotation_photos');
    }
}
