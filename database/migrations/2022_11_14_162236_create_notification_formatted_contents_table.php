<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('notification_formatted_contents', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('notification_id')->nullable();
            $table->foreign('notification_id')
                ->references('id')
                ->on('notifications')
                ->onDelete('CASCADE');

            $table->char('locale')
                ->default(\App\Enums\Locale::English);

            $table->string('title');
            $table->string('message')->nullable();
            $table->string('body')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('notification_formatted_contents');
    }
};
