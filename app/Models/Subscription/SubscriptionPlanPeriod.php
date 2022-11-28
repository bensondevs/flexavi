<?php

namespace App\Models\Subscription;

use App\Observers\SubscriptionPlanPeriodObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Cashier\Coupon\CouponOrderItemPreprocessor;
use Laravel\Cashier\Order\OrderItemPreprocessorCollection;
use Laravel\Cashier\Order\PersistOrderItemsPreprocessor;
use Laravel\Cashier\Plan\Plan as CashierPlan;
use Mollie\Api\Types\PaymentMethod;
use Money\Money;

class SubscriptionPlanPeriod extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * Set timestamp each time model is saved
     *
     * @var bool
     */
    public $timestamps = true;
    /**
     * Set whether primary key use incrementing value or not
     *
     * @var bool
     */
    public $incrementing = false;
    /**
     * Set which columns are searchable
     *
     * @var array
     */
    public array $searchableFields = ['name', 'description'];

    protected array $orderItemPreprocessors = [
        CouponOrderItemPreprocessor::class,
        PersistOrderItemsPreprocessor::class
    ];

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'subscription_plan_periods';

    /**
     * Table name primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = [
        'subscription_plan_id',
        'name',
        'description',
        'interval',
        'amount',
        'currency',
        'first_payment_description',
        'first_payment_amount',
        'first_payment_currency',

    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::observe(SubscriptionPlanPeriodObserver::class);
        static::addGlobalScope('order', function ($query) {
            $query->orderBy('amount', 'ASC');
        });
    }

    /**
     * Get the subscription plan
     *
     * @return BelongsTo
     */
    public function subscriptionPlan(): BelongsTo
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id', 'id');
    }

    /**
     * Builds a Cashier plan from the current model.
     *
     * @returns CashierPlan
     */
    public function buildCashierPlan(): CashierPlan
    {
        $plan = new CashierPlan($this->name);
        return $plan->setAmount($this->getAmount())
            ->setInterval($this->interval)
            ->setDescription($this->description)
            ->setFirstPaymentMethod((array)PaymentMethod::CREDITCARD)
            ->setFirstPaymentAmount($this->getFirstPaymentAmount())
            ->setFirstPaymentDescription($this->first_payment_description)
            ->setFirstPaymentRedirectUrl(config('cashier.first_payment.redirect_url'))
            ->setFirstPaymentWebhookUrl(config('cashier.first_payment.webhook_url'))
            ->setOrderItemPreprocessors(OrderItemPreprocessorCollection::fromArray($this->orderItemPreprocessors));
    }

    /**
     * Get amount in money format
     *
     * @return Money
     */
    public function getAmount(): Money
    {
        return mollie_array_to_money([
            'value' => $this->amount,
            'currency' => $this->currency,
        ]);
    }

    /**
     * Get first payment amount in money format
     *
     * @return Money
     */
    public function getFirstPaymentAmount(): Money
    {
        return mollie_array_to_money([
            'value' => $this->first_payment_amount,
            'currency' => $this->first_payment_currency,
        ]);
    }

    /**
     * Create callable `formatted_amount`
     * This is used to display amount in human-readable format
     *
     * @return string
     */
    public function getFormattedAmountAttribute(): string
    {
        return currencyFormat($this->attributes['amount']);
    }
}
