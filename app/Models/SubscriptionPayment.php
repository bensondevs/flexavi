<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class SubscriptionPayment extends Model
{
    use Searchable;
    use SoftDeletes;

    protected $table = 'subscription_payments';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'company_id',
        'company_subscription_id',
        'pricing_id',
        'payment_method',
        'bank_information_json',
        'paid_amount',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($subscriptionPayment) {
            $subscriptionPayment->id = Uuid::generate()->string;
    	});
    }

    public function user()
    {
        return $this->belongsTo(
            'App\Models\User',
            'id',
            'user_id'
        );
    }

    public function company()
    {
        return $this->belongsTo(
            'App\Models\Company',
            'id',
            'company_id'
        );
    }

    public function subscription()
    {
        return $this->belongsTo(
            'App\Models\CompanySubscription',
            'id',
            'company_subscription_id'
        );
    }

    public function pricing()
    {
        return $this->hasOne(
            'App\Models\Pricing',
            'id',
            'pricing_id'
        );
    }

    public function getBankInformationAttribute()
    {
        return json_decode(
            $this->attributes['bank_information_json'], 
            true
        );
    }

    public function setBankInformationAttribute(array $bankInformation)
    {
        $this->attributes['bank_information_json'] = json_encode($bankInformation);
    }
}