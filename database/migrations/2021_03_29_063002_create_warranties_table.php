<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\Warranty\WarrantyStatus;

class CreateWarrantiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warranties', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('CASCADE');

            $table->uuid('appointment_id');
            $table->foreign('appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('CASCADE');

            $table->uuid('for_appointment_id');
            $table->foreign('for_appointment_id')
                ->references('id')
                ->on('appointments')
                ->onDelete('CASCADE');

            $table->tinyInteger('status')->default(1);

            $table->text('problem_description')->nullable();
            $table->text('fixing_description')->nullable();

            $table->text('internal_note')->nullable();
            $table->text('customer_note')->nullable();

            $table->double('amount', 8, 2)->default(0);
            $table->double('paid_amount', 8, 2)->default(0);

            $table->timestamps();
            $table->timestamp('in_process_at')->nullable();
            $table->timestamp('fixed_at')->nullable();
            $table->timestamp('unfixed_at')->nullable();
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
        Schema::dropIfExists('warranties');
    }
}
