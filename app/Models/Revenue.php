<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class Revenue extends Model
{
    use SoftDeletes;
    use Searchable;

    protected $table = 'revenues';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'revenue_name',
    ];

    protected $fillable = [
        'company_id',

        'revenueable_type',
        'revenueable_id',

        'revenue_name',
        'amount',
        'paid_amount',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($revenue) {
            $revenue->id = Uuid::generate()->string;
    	});
    }

    public function getUnpaidAmountAttribute()
    {
        $amount = $this->attributes['amount'];
        $paid = $this->attributes['paid_amount'];

        return $amount - $paid;
    }

    public function getIsSettledAttribute()
    {
        $unpaid = $this->getUnpaidCostAttribute();
        return $unpaid <= 0;
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function revenueable()
    {
        return $this->morphTo();
    }

    public function receipt()
    {
        return $this->morphOne(Receipt::class);
    }
}