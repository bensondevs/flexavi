<?php

use App\Enums\ExecuteWork\WarrantyTimeType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkWarrantiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_warranties', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('execute_work_photo_id');
            $table->foreign('execute_work_photo_id')
                ->references('id')
                ->on('execute_work_photos')
                ->onDelete('CASCADE');

            $table->double('quantity', 8, 2)->default(0);
            $table->string('quantity_unit')->nullable();


            $table->uuid('work_service_id')->nullable();
            $table->foreign('work_service_id')
                ->references('id')
                ->on('work_services')
                ->onDelete('SET NULL');

            $table->double('unit_price', 10, 2);
            $table->double('total_price')->default(0);
            $table->double('total_paid')->default(0);

            $table->integer('warranty_time_value')->nullable();
            $table->tinyInteger('warranty_time_type')->nullable();

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
        Schema::dropIfExists('work_warranties');
    }
}
