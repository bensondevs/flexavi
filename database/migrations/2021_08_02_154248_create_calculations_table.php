<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalculationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calculations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuidMorphs('calculationable');

            $table->json('kpi_data')->nullable();

            $table->double('total_revenues', 15, 2)->default(0);
            $table->double('total_costs', 15, 2)->default(0);
            $table->double('vat_amount', 14, 2)->default(0);
            $table->double('gross_profit', 15, 2)->default(0);

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
        Schema::dropIfExists('calculations');
    }
}
