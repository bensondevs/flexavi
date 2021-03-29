<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Quotation extends Model
{
    protected $table = 'quotations';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'creator_id',
        'customer_id',
        'appoinment_id',

        'quotation_type',
        'quotation_description',
        'expiry_date',
        'status',
        'payment_method',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($quotation) {
            $quotation->id = Uuid::generate()->string;
    	});
    }

    public function customer()
    {
        return $this->hasOne(
            'App\Models\Customer', 
            'customer_id', 
            'id'
        );
    }

    public function photos()
    {
        return $this->hasMany(
            'App\Models\QuotationPhoto',
            'quotation_id',
            'id'
        );
    }

    public function creator()
    {
        return $this->belongsTo(
            'App\Models\User', 
            'creator_id', 
            'id'
        );
    }

    public function appointment()
    {
        return $this->belongsTo(
            'App\Models\Appointment', 
            'appoinment_id', 
            'id'
        );
    }
}