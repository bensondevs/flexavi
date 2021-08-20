<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorklistsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('worklists', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('CASCADE');

            $table->uuid('workday_id')->nullable();
            $table->foreign('workday_id')
                ->references('id')
                ->on('workdays')
                ->onDelete('CASCADE');

            $table->date('start')->nullable();
            $table->date('end')->nullable();

            $table->tinyInteger('status')->default(1);

            $table->string('worklist_name')->default('Untitled Worklist');

            $table->timestamps();
            $table->timestamp('processed_at')->nullable();
            $table->timestamp('calculated_at')->nullable();
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
        Schema::dropIfExists('worklists');
    }
}
