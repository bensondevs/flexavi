<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('module_permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('module_id');
            $table->foreign('module_id')
                ->references('id')
                ->on('modules')
                ->onDelete('CASCADE');

            $table->uuid('permission_id');
            $table->foreign('permission_id')
                ->references('id')
                ->on('permissions')
                ->onDelete('CASCADE');

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
        Schema::dropIfExists('module_permissions');
    }
};
