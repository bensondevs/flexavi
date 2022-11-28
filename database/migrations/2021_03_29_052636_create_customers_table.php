<?php

use App\Enums\Customer\CustomerAcquisition as Acquisition;
use App\Enums\Customer\CustomerSalutation;
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

            $table->tinyInteger('salutation')->default(CustomerSalutation::Mr);

            $table->tinyInteger('acquired_through')
                ->default(Acquisition::Website);
            $table->uuid('acquired_by')->nullable();
            $table->foreign('acquired_by')
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL');

            $table->string('fullname');

            $table->string('email')->nullable();

            $table->string('phone')->unique();
            $table->string('second_phone')->nullable();

            $table->char('unique_key');

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
