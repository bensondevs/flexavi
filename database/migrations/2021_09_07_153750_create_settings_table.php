<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\Setting\{
    SettingType as Type,
    SettingInputType as InputType,
    SettingValueDataType as ValueDataType
};

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->tinyInteger('type')->default(Type::System);
            $table->string('key');

            $table->tinyInteger('input_type')->default(InputType::Text);
            $table->json('options')->nullable();
            
            $table->tinyInteger('value_data_type')->default(ValueDataType::String);

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
        Schema::dropIfExists('settings');
    }
}