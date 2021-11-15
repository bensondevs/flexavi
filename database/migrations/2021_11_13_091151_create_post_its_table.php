<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePostItsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('post_its', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('CASCADE');

            $table->uuid('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('CASCADE');

            $table->longText('content');

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
        Schema::dropIfExists('post_its');
    }
}
