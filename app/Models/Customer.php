<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Webpatser\Uuid\Uuid;

class Customer extends Model
{
    use HasApiTokens;

    protected $table = 'customers';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        
        'fullname',
        'salutation',
        'address',
        'house_number',
        'zipcode',
        'city',
        'province',
        'email',
        'phone',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($customer) {
            $customer->id = Uuid::generate()->string;
    	});
    }

    public function company()
    {
        return $this->belongsTo(
            'App\Models\Company', 
            'company_id', 
            'id'
        );
    }
}