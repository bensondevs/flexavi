<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\Warranty\WarrantyStatus;

class CreateWarrantyWorksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warranty_works', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('warranty_id');
            $table->foreign('warranty_id')
                ->references('id')
                ->on('warranties')
                ->onDelete('CASCADE');

            $table->uuid('work_id');
            $table->foreign('work_id')
                ->references('id')
                ->on('works')
                ->onDelete('CASCADE');

            $table->tinyInteger('status')->default(WarrantyStatus::Created);
            $table->text('note')->nullable();
            $table->integer('amount')->default(1);

            $table->timestamps();
            $table->timestamp('in_process_at')->nullable();
            $table->timestamp('finsihed_at')->nullable();
            $table->timestamp('unfinsihed_at')->nullable();
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
        Schema::dropIfExists('warranty_works');
    }
}
