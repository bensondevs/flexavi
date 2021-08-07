<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('costs', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('CASCADE');

            /*$table->uuid('workday_id')->nullable();
            $table->foreign('workday_id')
                ->references('id')
                ->on('workdays')
                ->onDelete('CASCADE');

            $table->uuid('worklist_id')->nullable();
            $table->foreign('worklist_id')
                ->references('id')
                ->on('worklists')
                ->onDelete('CASCADE');

            $table->uuidMorphs('costable');*/

            $table->string('cost_name');
            $table->double('amount', 8, 2);
            $table->double('paid_amount', 8, 2)->default(0);

            $table->string('receipt_path')->nullable();

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
        Schema::dropIfExists('costs');
    }
}
