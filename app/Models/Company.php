<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use \Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use SoftDeletes, CascadeSoftDeletes;
    use Searchable;

    protected $cascadeDeletes = [
        'owners', 
        'employees', 
        'customers',
        'appointments',
        'quotations',
        'workContracts',
        'inspections',
        'invoices',
        'paymentTerms',
        'cars',
        'schedules',
        'taxtSettings',
        'workdays',
    ];
    protected $dates = ['deleted_at'];

    protected $table = 'companies';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'company_name',

        'email',
        'phone_number',
        'vat_number',
        'commerce_chamber_number',
        'company_website_url',
    ];

    protected $fillable = [
        'company_name',

        'email',
        'phone_number',
        'vat_number',
        'commerce_chamber_number',
        'company_logo_path',
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
        return $this->hasMany(Owner::class);
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    public function workContracts()
    {
        return $this->hasMany(WorkContract::class);
    }

    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function paymentTerms()
    {
        return $this->hasMany(PaymentTerm::class);
    }

    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function taxSetting()
    {
        return $this->hasOne(TaxSetting::class);
    }

    public function workdays()
    {
        return $this->hasMany(CompanyWorkday::class);
    }

    public function worklists()
    {
        return $this->hasMany(Worklist::class);
    }

    public function settings()
    {
        return $this->morphMany(Setting::class, 'settingable');
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
            'zipcode' => $value['zipcode'],
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
            'zipcode' => $value['zipcode'],
            'city' => $value['city'],
        ];

        $this->attributes['invoicing_address'] = json_encode($_address);
    }

    public function setCompanyLogoAttribute($logoFile)
    {
        $path = 'uploads/companies/logos/';
        $logo = uploadFile($logoFile, $path);

        $this->attributes['company_logo_path'] = $logo->path;
    }

    public function getCompanyLogoUrlAttribute()
    {
        $path = $this->attributes['company_logo_path'];
        return Storage::url($path);
    }
}