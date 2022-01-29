<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Observers\SubscriptionObserver as Observer;

use App\Enums\Subscription\SubscriptionStatus as Status;
use App\Enums\SubscriptionPayment\SubscriptionPaymentStatus as PaymentStatus;

class Subscription extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    /**
     * Database table name
     * 
     * @var string
     */
    protected $table = 'subscriptions';

    /**
     * Table name primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

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
    protected $searchable = [
        //
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',
        'subscription_plan_id',
        'status',
        'subscription_start',
        'subscription_end',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();
        self::observe(Observer::class);
    }

    /**
     * The "booted" model of the model. This method will
     * contain all additional queries that should be loaded
     * all the time when model is querying
     * 
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope();
    }

    /**
     * Create callable method of "forCompany(Company $company)"
     * This callable method will query only subscription that is
     * belongs to a certain company only
     * 
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @param  \App\Models\Company  $company
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForCompany(Builder $query, Company $company)
    {
        return $query->where('company_id', $company->id);
    }

    /**
     * Create callable method of "activable()"
     * This callable method will query only subscription that has
     * payment that has status of paid
     * 
     * @param  Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActivable(Builder $query)
    {
        return $query
            ->where('status', Status::Inactive)
            ->whereHas('payment', function ($payment) {
                $payment->where('status', PaymentStatus::Settled);
            });
    }

    /**
     * Create callable attribute of "human_subscription_start"
     * This callable attribute will return the human subscription 
     * start date time
     * 
     * @return string
     */
    public function getHumanSubscriptionStartAttribute()
    {
        $start = $this->attributes['subscription_start'];
        return carbon()->parse($start)->format('M d, Y H:i:s');
    }

    /**
     * Create callable attribute of "human_subscription_end"
     * This callable attribute will return the humen subscription
     * end date time
     * 
     * @return string
     */
    public function getHumanSubscriptionEndAttribute()
    {
        $end = $this->attributes['subscription_end'];
        return carbon()->parse($end)->format('M d, Y H:i:s');
    }

    /**
     * Get company that subscribed to this subscription
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get subscription plan
     */
    public function plan()
    {
        return $this->belongsTo(SubscriptionPlan::class);
    }

    /**
     * Get subscription payment
     */
    public function payment()
    {
        return $this->hasOne(SubscriptionPayment::class);
    }

    /**
     * Create payment for the subscription
     * 
     * @param  int  $paymentMethod
     * @return \App\Models\SubscriptionPayment
     */
    public function createPayment(int $paymentMethod)
    {
        return $this->payment = SubscriptionPayment::create([
            'user_id' => auth()->user()->id,
            'company_id' => $this->attributes['company_id'],
            'payment_method' => $paymentMethod,
            'amount' => $this->plan->price,
        ]);
    }

    /**
     * Create subscription renewal of current subscription
     * 
     * @return self
     */
    public function createRenewal()
    {
        $renewal = self::create([
            'previous_subscription_id' => $this->attributes['id'],
            'company_id' => $this->attributes['company_id'],
            'subscription_plan_id' => $this->attributes['subscription_plan_id'],
        ]);

        $this->attributes['renew_subscription_id'] = $renewal->id;
        $this->save();

        return $renewal;
    }

    /**
     * Start the subscription from now and get duration
     * from the subscription plan
     * 
     * @return bool
     */
    public function start()
    {
        $plan = $this->plan;
        $durationDays = $plan->duration_days;

        $now = now()->copy();

        $this->attributes['subscription_start'] = $now;
        $this->attributes['subscription_end'] = $now->addDays($durationDays);

        return $this->save();
    }

    /**
     * Set subscription as activated and mark the time of 
     * the subscription activation
     * 
     * @return bool
     */
    public function setActive()
    {
        $this->attributes['status'] = Status::Active;
        $this->attributes['activated_at'] = now();

        return $this->save();
    }

    /**
     * Set subscription as expired and mark the time of the
     * subscription expired at
     * 
     * @return bool
     */
    public function setExpired()
    {
        $this->attributes['status'] = Status::Expired;

        return $this->save();
    }

    /**
     * Set subscription as terminated and mark the time of
     * the subscription termination
     * 
     * [THIS WILL DESTROY THE COMPANY DATA WITHIN 14 DAYS]
     * 
     * @return bool
     */
    public function setTerminated()
    {
        $this->attributes['status'] = Status::Terminated;
        $this->attributes['terminated_at'] = now();

        return $this->save();
    }
}