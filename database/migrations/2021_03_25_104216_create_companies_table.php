<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('company_name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->string('commerce_chamber_number')->nullable();
            $table->string('company_website_url')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('will_be_deleted_at')->nullable();
            $table->timestamp('will_be_permanently_deleted_at')->nullable();

            $table->string('mollie_customer_id')->nullable();
            $table->string('mollie_mandate_id')->nullable();
            $table->decimal('tax_percentage', 6, 4)->default(0); // optional
            $table->dateTime('trial_ends_at')->nullable(); // optional
            $table->text('extra_billing_information')->nullable(); // optional

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
        Schema::dropIfExists('companies');
    }
}
