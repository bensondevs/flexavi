<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\SettingValue\{
    SettingValueType as Type,
    SettingValueDataType as DataType
};

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

            $table->uuid('setting_key_id');
            $table->foreign('setting_key_id')
                ->references('id')
                ->on('setting_keys')
                ->onDelete('CASCADE');

            $table->tinyInteger('value_type')->default(Type::Default);
            $table->nullableUuidMorphs('value_setter');

            $table->tinyInteger('data_type')->default(DataType::Text);
            $table->longText('value');

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
