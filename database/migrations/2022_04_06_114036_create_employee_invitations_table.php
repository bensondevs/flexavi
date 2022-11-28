<?php

use App\Enums\Employee\EmployeeType;
use App\Enums\EmployeeInvitation\EmployeeInvitationStatus as Status;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('employee_invitations', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('company_id')->nullable();
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('SET NULL');

            // User related input
            $table->string('registration_code');
            $table->string('name');
            $table->date('birth_date')->nullable();
            $table->char('phone');
            $table->string('invited_email');

            // Employee related input
            $table->string('title')->nullable();
            $table->string('role')->default(EmployeeType::Roofer);
            $table->string('contract_file_path')->nullable();

            $table->tinyInteger('status')->default(Status::Active);
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamp('marked_expired_at')->nullable();

            $table->datetime('expiry_time');
            $table->json('permissions')->nullable();

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
        Schema::dropIfExists('employee_invitations');
    }
}
