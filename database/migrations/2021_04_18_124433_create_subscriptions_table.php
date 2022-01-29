<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use App\Enums\Subscription\SubscriptionStatus as Status;

class CreateSubscriptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->uuid('previous_subscription_id')->nullable();
            $table->uuid('renew_subscription_id')->nullable();

            $table->uuid('company_id');
            $table->foreign('company_id')
                ->references('id')
                ->on('companies')
                ->onDelete('CASCADE');

            $table->uuid('subscription_plan_id');
            $table->foreign('subscription_plan_id')
                ->references('id')
                ->on('subscription_plans')
                ->onDelete('CASCADE');

            $table->tinyInteger('status')->default(Status::Inactive);
            $table->boolean('recurring')->default(false);

            $table->datetime('subscription_start')->nullable();
            $table->datetime('subscription_end')->nullable();

            $table->timestamps();
            $table->softDeletes();

            $table->timestamp('activated_at')->nullable();
            $table->timestamp('terminated_at')->nullable();
            $table->timestamp('renewed_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('subscriptions');
    }
}
