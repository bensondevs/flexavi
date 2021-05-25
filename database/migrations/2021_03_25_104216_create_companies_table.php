<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->string('company_name');

            $table->json('visiting_address');
            $table->json('invoicing_address');

            $table->string('email');
            $table->string('phone_number');

            $table->string('vat_number'); // Need validation from API
            $table->string('commerce_chamber_number');

            $table->string('company_logo_url')->nullable();
            $table->string('company_website_url')->nullable();

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
        Schema::dropIfExists('companies');
    }
}
