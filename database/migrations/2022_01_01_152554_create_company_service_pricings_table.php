<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyServicePricingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_service_pricings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            
            $table->uuid('company_service_id');
            $table->foreign('company_service_id')
                ->references('id')
                ->on('company_services')
                ->onDelete('CASCADE');

            $table->string('pricing_name');
            $table->double('price', 20, 2)->default(0);

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
        Schema::dropIfExists('company_service_pricings');
    }
}
