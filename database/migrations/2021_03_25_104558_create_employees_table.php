<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\Employee\EmployeeType;
use App\Enums\Employee\EmploymentStatus;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('user_id')->nullable();
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('SET NULL');

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('SET NULL');

            $table->string('photo_path')->nullable();

            $table->char('title');
            $table->tinyInteger('employee_type')->default(EmployeeType::Administrative);
            $table->tinyInteger('employment_status')->default(EmploymentStatus::Active);

            $table->text('address');
            $table->char('house_number');
            $table->char('house_number_suffix')->nullable();
            $table->char('zipcode');
            $table->char('city');
            $table->char('province');
            
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
        Schema::dropIfExists('employees');
    }
}
