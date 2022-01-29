<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Observers\SubscriptionPaymentObserver as Observer;
use App\Enums\SubscriptionPayment\{
    SubscriptionPaymentStatus as Status,
    SubscriptionPaymentMethod as Method
};

class SubscriptionPayment extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    /**
     * Database table name
     * 
     * @var string
     */
    protected $table = 'subscription_payments';

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
        'user_id',
        'company_id',
        'subscription_id',
        'status',
        'payment_method',
        'amount',
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
     * Create callable attribute of "status_description"
     * This callable attribute will return description 
     * of the current payment status
     * 
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];
        return Status::getDescription($status);
    }

    /**
     * Create callable attribute of "payment_method_description"
     * This callable attribute will return description of
     * the current payment method
     * 
     * @return string
     */
    public function getPaymentMethodStatusAttribute()
    {
        $method = $this->attributes['payment_method'];
        return Method::getDescription($method);
    }

    /**
     * Get the user that do the payment
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get company of the subscription
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get subscription parent of current payment
     */
    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }
}