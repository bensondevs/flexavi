<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\SettingValue\SettingValueType as Type;

class CreateSettingValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('setting_values', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('setting_id');
            $table->foreign('setting_id')
                ->references('id')
                ->on('settings')
                ->onDelete('CASCADE');

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('CASCADE');
                
            $table->tinyInteger('value_type')->default(Type::Default);
            $table->longText('value')->nullable();

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
        Schema::dropIfExists('setting_values');
    }
}
