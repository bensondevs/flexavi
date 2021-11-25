<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SubscriptionPayment extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'subscription_payments';

    /**
     * The primary key of the model
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Timestamp recording
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Set whether primary key use increment or not
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'company_subscription_id',
        'pricing_id',
        'payment_method',
        'bank_information_json',
        'paid_amount',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @static
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($payment) {
            $payment->id = Uuid::generate()->string;
    	});
    }

    /**
     * Get user that pays the subscription
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get company that bears the subscription
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get subscription
     */
    public function subscription()
    {
        return $this->belongsTo(CompanySubscription::class);
    }

    /**
     * Get pricing of the subscription
     */
    public function pricing()
    {
        return $this->hasOne(Pricing::class);
    }

    /**
     * Create callable "bank_information" attribute
     * This callable attribute will return array of bank information
     * 
     * @return array
     */
    public function getBankInformationAttribute()
    {
        $bankInformation = $this->attributes['bank_information_json'];
        return json_decode($bankInformation, true);
    }

    /**
     * Create settable "bank_information" attribute
     * This settable attribute will allow insertion to bank information 
     * using array
     * 
     * @param array  $bankInformation
     * @return void 
     */
    public function setBankInformationAttribute(array $bankInformation)
    {
        $this->attributes['bank_information_json'] = json_encode($bankInformation);
    }
}