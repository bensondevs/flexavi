<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCarsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cars', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('CASCADE');

            $table->string('car_image_path')->nullable();

            $table->string('brand');
            $table->string('model');
            $table->integer('year');

            $table->string('car_name');
            $table->string('car_license');

            $table->boolean('insured')->default(0);
            $table->double('insurance_tax', 8, 2)->default(0);

            $table->tinyInteger('status')->default(1);

            $table->tinyInteger('max_passanger')->nullable();
            $table->timestamp('apk')->nullable();
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
        Schema::dropIfExists('cars');
    }
}
