<?php

use App\Enums\RegisterInvitation\RegisterInvitationStatus;
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
    public function up(): void
    {
        Schema::create('register_invitations', function (Blueprint $table) {
            $table->id();

            $table->uuid('invitationable_id');
            $table->string('invitationable_type');
            $table->string('registration_code');
            $table->datetime('expiry_time');
            $table->tinyInteger('status')->default(RegisterInvitationStatus::Active);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('register_invitations');
    }
}
