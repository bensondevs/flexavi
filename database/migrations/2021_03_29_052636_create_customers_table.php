<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCustomersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('SET NULL');

            $table->string('registered_from')->default('web');

            $table->string('fullname');

            $table->string('email')->nullable();

            $table->string('phone')->unique();
            $table->string('second_phone')->nullable();

            $table->char('unique_key');

            $table->text('address');
            $table->char('house_number');
            $table->char('house_number_suffix')->nullable();
            $table->char('zipcode');
            $table->char('city');
            $table->char('province');

            $table->rememberToken();
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
        Schema::dropIfExists('customers');
    }
}
