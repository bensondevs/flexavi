<?php

namespace App\Models\Subscription;

use App\Observers\SubscriptionPlanObserver;
use Illuminate\Database\Eloquent\{Model, Relations\HasOne, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;


class SubscriptionPlan extends Model
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

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'description' => 'array',
    ];

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'subscription_plans';

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
        'name',
        'description',
        'price',
        'is_trial'
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
        self::observe(SubscriptionPlanObserver::class);
    }

    /**
     * Create callable "first_period_formatted_price" attribute
     * This callable attribute will return currency formatted amount
     *
     * @return string
     */
    public function getFirstPeriodFormattedPriceAttribute(): string
    {
        $firstPeriod = $this->periods->first();
        $amount = $firstPeriod->price;

        return currency_format($amount);
    }


    /**
     * Create callable "formatted_base_price_attribute" attribute
     * This callable attribute will return base price
     * @return bool|string
     */
    public function getFormattedBasePriceAttribute(): bool|string
    {
        return currency_format($this->attributes['base_price']);
    }

    /**
     * Create callable "first_period_price" attribute
     * This callable attribute will return currency formatted amount
     *
     * @return float
     */
    public function getFirstPeriodPriceAttribute(): float
    {
        $firstPeriod = $this->periods->first();

        return $firstPeriod->price;
    }

    /**
     * Create callable "first_period_duration_days" attribute
     * This callable attribute will return currency formatted amount
     *
     * @return string
     */
    public function getFirstPeriodDurationDaysAttribute(): string
    {
        $firstPeriod = $this->periods->first();

        return $firstPeriod->duration_days;
    }

    /**
     * Get the subscription plan periods
     *
     * @return HasMany
     */
    public function subscriptionPlanPeriods(): HasMany
    {
        return $this->hasMany(SubscriptionPlanPeriod::class)
            ->orderBy('discount', 'ASC');
    }

    /**
     * Get the higher discount of period
     *
     * @return HasOne
     */
    public function higherDiscountOfPeriod(): HasOne
    {
        return $this->hasOne(SubscriptionPlanPeriod::class)
            ->orderBy('discount', 'DESC');
    }

    /**
     * Check if model trial
     *
     * @return bool
     */
    public function isTrial(): bool
    {
        return $this->attributes['is_trial'] === 1;
    }
}
