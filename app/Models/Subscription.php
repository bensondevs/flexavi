<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class Subscription extends Model
{
    use Searchable;
    use SoftDeletes;

    protected $table = 'subscriptions';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'status',
        'subscription_start',
        'subscription_end',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($companySubscription) {
            $companySubscription->id = Uuid::generate()->string;
    	});
    }

    public function company()
    {
        return $this->belongsTo(
            'App\Models\Company',
            'id',
            'company_id'
        );
    }
}