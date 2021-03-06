<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();

            // Personal Identity
            $table->string('fullname');
            $table->date('birth_date');
            $table->tinyInteger('id_card_type')->default(1);
            $table->char('id_card_number');
            $table->char('phone');
            $table->timestamp('phone_verified_at')->nullable();
            $table->string('profile_picture_path')->nullable();

            $table->string('registration_code')->nullable();

            // Authentication
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');

            // Social Media IDs
            $table->string('google_id')->nullable();
            $table->string('facebook_id')->nullable();

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
        Schema::dropIfExists('users');
    }
}
