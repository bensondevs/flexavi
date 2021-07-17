<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegisterInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_invitations', function (Blueprint $table) {
            $table->id();

            $table->string('registration_code');
            $table->string('invited_email');
            $table->json('attachments')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->datetime('expiry_time');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('register_invitations');
    }
}
