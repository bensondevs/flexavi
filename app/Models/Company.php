<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Company extends Model
{
    use SoftDeletes;

    protected $table = 'companies';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_name',

        'email',
        'phone_number',
        'vat_number',
        'commerce_chamber_number',
        'company_logo_url',
        'company_website_url',
    ];

    protected $casts = [
        'visiting_address' => 'array',
        'invoicing_address' => 'array',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function owners()
    {
        return $this->hasMany(
            'App\Models\Owner', 
            'company_id', 
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

    public function workContracts()
    {
        return $this->hasMany(
            'App\Models\WorkContract', 
            'company_id', 
            'id'
        );
    }

    public function inspections()
    {
        return $this->hasMany(
            'App\Models\Inspection',
            'company_id',
            'id'
        );
    }

    public function invoices()
    {
        return $this->hasMany(
            'App\Models\Invoice', 
            'company_id', 
            'id'
        );
    }

    public function paymentTerms()
    {
        return $this->hasMany(
            'App\Models\PaymentTerm',
            'company_id',
            'id'
        );
    }

    public function cars()
    {
        return $this->hasMany(
            'App\Models\Car',
            'company_id',
            'id'
        );
    }

    public function schedules()
    {
        return $this->hasMany(
            'App\Models\Schedule',
            'company_id',
            'id'
        );
    }

    public function workActivities()
    {
        return $this->hasMany(
            'App\Models\WorkActivity',
            'company_id',
            'id'
        );
    }

    public function taxSetting()
    {
        return $this->hasOne(
            'App\Models\TaxSetting',
            'company_id',
            'id'
        );
    }

    public function workdays()
    {
        return $this->hasMany(
            'App\Models\CompanyWorkday',
            'company_id',
            'id'
        );
    }

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($company) {
            $company->id = Uuid::generate()->string;
    	});
    }

    public function getPrimeOwnerAttribute()
    {
        return $this->owners
            ->where('is_prime_owner', true)
            ->first();
    }

    public function getVisitingAddressAttribute()
    {
        $address = $this->attributes['visiting_address'];
        
        return json_decode($address, true);
    }

    public function setVisitingAddressAttribute(array $value)
    {
        $_address = [
            'street' => $value['street'],
            'house_number' => $value['house_number'],
            'house_number_suffix' => isset($value['house_number_suffix']) ?
                $value['house_number_suffix'] : null,
            'zip_code' => $value['zip_code'],
            'city' => $value['city'],
        ];

        $this->attributes['visiting_address'] = json_encode($_address);
    }

    public function getInvoicingAddressAttribute()
    {
        $address = $this->attributes['invoicing_address'];

        return json_decode($address, true);
    }

    public function setInvoicingAddressAttribute(array $value)
    {
        $_address = [
            'street' => $value['street'],
            'house_number' => $value['house_number'],
            'house_number_suffix' => isset($value['house_number_suffix']) ?
                $value['house_number_suffix'] : null,
            'zip_code' => $value['zip_code'],
            'city' => $value['city'],
        ];

        $this->attributes['invoicing_address'] = json_encode($_address);
    }

    public function setCompanyLogoAttribute($logoFile)
    {
        $path = 'storage/companies/logos/';
        $uploadedLogoPath = uploadFile($logoFile, $path);
        $logoUrl = asset($uploadedLogoPath);

        $this->attributes['company_logo_url'] = $logoUrl;
    }
}