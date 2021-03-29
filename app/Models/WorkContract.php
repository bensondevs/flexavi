<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class WorkContract extends Model
{
    protected $table = 'work_contracts';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        'customer_id',
        'contract_date_start',
        'contract_date_end',
        'include_weekend',
        'price',
        'payment_method',
        'status',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($workContract) {
            $workContract->id = Uuid::generate()->string;
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

    public function works()
    {
        return $this->hasMany(
            'App\Models\Work', 
            'work_contract_id',
            'id'
        );
    }

    public function warranty()
    {
        return $this->hasOne(
            'App\Models\Warranty',
            'work_contract_id',
            'id'
        );
    }
}