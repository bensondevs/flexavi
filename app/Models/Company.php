<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Company extends Model
{
    protected $table = 'companies';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'owner_id',

        'visiting_address_json',
        'invoicing_address_json',

        'email',
        'phone_number',
        'vat_number',
        'commerce_chamber_number',
        'company_logo_url',
        'company_website_url',
    ];

    protected $hidden = [
        
    ];

    public function owner()
    {
        return $this->belongsTo(
            'App\Models\Owner', 
            'user_id', 
            'id'
        );
    }

    public function employees()
    {
        return $this->hasMany(
            'App\Models\Employee', 
            'company_id', 
            'id'
        );
    }

    public function customers()
    {
        return $this->hasMany(
            'App\Models\Customer', 
            'company_id', 
            'id'
        );
    }

    public function appointments()
    {
        return $this->hasMany(
            'App\Models\Appointment',
            'company_id',
            'id'
        );
    }

    public function quotations()
    {
        return $this->hasMany(
            'App\Models\Quotation',
            'id',
            'company_id'
        );
    }

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($company) {
            $company->id = Uuid::generate()->string;
    	});
    }

    public function getVisitingAddressAttribute()
    {
        return json_decode(
            $this->attributes['visiting_address_json'], 
            true
        );
    }

    public function setVisitingAddressAttribute(array $value)
    {
        $_address = [
            'street' => $value['street'],
            'house_number' => $value['house_number'],
            'house_number_suffix' => $value['house_number_suffix'],
            'zip_code' => $value['zip_code'],
            'city' => $value['city'],
        ];

        $this->attributes['visiting_address_json'] = json_encode($_address);
    }

    public function getInvoicingAddressAttribute()
    {
        return json_decode(
            $this->attributes['invoicing_address_json'], 
            true
        );
    }

    public function setInvoicingAddressAttribute(array $value)
    {
        $_address = [
            'street' => $value['street'],
            'house_number' => $value['house_number'],
            'house_number_suffix' => $value['house_number_suffix'],
            'zip_code' => $value['zip_code'],
            'city' => $value['city'],
        ];

        $this->attributes['invoicing_address_json'] = json_encode($_address);
    }
}