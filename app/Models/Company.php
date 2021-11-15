<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use \Illuminate\Support\Facades\Storage;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Enums\Address\AddressType;

class Company extends Model
{
    use HasFactory;
    use SoftDeletes, CascadeSoftDeletes;
    use Searchable;
    /**
     * Related tables that will be soft-deleted in model soft-delete
     * 
     * @var array
     */
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

    /**
     * Soft delete column marker
     * 
     * @var array
     */
    protected $dates = ['deleted_at'];

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'companies';

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
     * Set which columns are searchable
     * 
     * @var array
     */
    protected $searchable = [
        'company_name',

        'email',
        'phone_number',
        'vat_number',
        'commerce_chamber_number',
        'company_website_url',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_name',

        'email',
        'phone_number',
        'vat_number',
        'commerce_chamber_number',
        'company_logo_path',
        'company_website_url',
    ];

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        self::creating(function ($company) {
            if (! $company->id) {
                $company->id = Uuid::generate()->string;
            }
        });
    }

    /**
     * Get the company owners
     */
    public function owners()
    {
        return $this->hasMany(Owner::class)->whereNotNull('user_id');
    }

    /**
     * Get the company addresses
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Get the company employees
     */
    public function employees()
    {
        return $this->hasMany(Employee::class)->whereNotNull('user_id');
    }

    /**
     * Get the company customers
     */
    public function customers()
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Get the company appointments
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the company sub-appointments
     */
    public function subAppointments()
    {
        return $this->hasMany(SubAppointment::class);
    }

    /**
     * Get the company quotations
     */
    public function quotations()
    {
        return $this->hasMany(Quotation::class);
    }

    /**
     * Get the company work contracts
     */
    public function workContracts()
    {
        return $this->hasMany(WorkContract::class);
    }

    /**
     * Get the company inspections
     */
    public function inspections()
    {
        return $this->hasMany(Inspection::class);
    }

    /**
     * Get the company invoices
     */
    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the company invoice items
     */
    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    /**
     * Get the company payment terms
     */
    public function paymentTerms()
    {
        return $this->hasMany(PaymentTerm::class);
    }

    /**
     * Get the company cars
     */
    public function cars()
    {
        return $this->hasMany(Car::class);
    }

    /**
     * Get the company car register times
     */
    public function carRegisterTimes()
    {
        return $this->hasMany(CarRegisterTime::class);
    }

    /**
     * Get the company costs
     */
    public function costs()
    {
        return $this->hasMany(Cost::class);
    }

    /**
     * Get the company workdays
     */
    public function workdays()
    {
        return $this->hasMany(Workday::class);
    }

    /**
     * Get the company worklists
     */
    public function worklists()
    {
        return $this->hasMany(Worklist::class);
    }

    /**
     * Get the company settings
     */
    public function settings()
    {
        return $this->morphMany(Setting::class, 'settingable');
    }

    /**
     * Get prime owner of the company
     * 
     * @return App\Models\Owner  $owner
     */
    public function getPrimeOwnerAttribute()
    {
        $owners = $this->owners();
        if ($this->relationLoaded('owners')) {
            $owners = $this->owners;
        }

        return $owners->where('is_prime_owner', true)->first();
    }

    /**
     * Get visiting address of company
     * 
     * @return App\Models\Address  $address
     */
    public function getVisitingAddressAttribute()
    {
        $addresses = ($this->relationLoaded('addresses')) ?
            $this->addresses : $this->addresses();

        $type = AddressType::VisitingAddress;
        return $addresses->where('address_type', $type)->first();
    }

    /**
     * Set visiting address of company
     * 
     * @return App\Models\Address  $address
     */
    public function setVisitingAddressAttribute(array $value)
    {
        if ($address = $this->getVisitingAddressAttribute()) {
            $address->delete();
        }

        $address = new Address([
            'address_type' => AddressType::VisitingAddress,

            'addressable_id' => $this->attributes['id'],
            'addressable_type' => self::class,

            'address' => $value['address'],
            'house_number' => $value['house_number'],
            'house_number_suffix' => isset($value['house_number_suffix']) ?
                $value['house_number_suffix'] : null,
            'zipcode' => $value['zipcode'],
            'city' => $value['city'],
            'province' => $value['province'],
        ]);
        $address->save();
    }

    /**
     * Get invoicing address of company
     * 
     * @return App\Models\Address  $address
     */
    public function getInvoicingAddressAttribute()
    {
        $addresses = ($this->relationLoaded('addresses')) ?
            $this->addresses : $this->addresses();

        $type = AddressType::InvoicingAddress;
        return $addresses->where('address_type', $type)->first();
    }

    /**
     * Set invoicing address of company
     * 
     * @return App\Models\Address  $address
     */
    public function setInvoicingAddressAttribute(array $value)
    {
        if ($address = $this->getInvoicingAddressAttribute()) {
            $address->delete();
        }

        $address = new Address([
            'address_type' => AddressType::InvoicingAddress,

            'addressable_id' => $this->attributes['id'],
            'addressable_type' => self::class,

            'address' => $value['address'],
            'house_number' => $value['house_number'],
            'house_number_suffix' => isset($value['house_number_suffix']) ?
                $value['house_number_suffix'] : null,
            'zipcode' => $value['zipcode'],
            'city' => $value['city'],
            'province' => $value['province'],
        ]);
        $address->save();
    }

    /**
     * Set company logo
     * 
     * @param mixed  $logoFile
     * @return void
     */
    public function setCompanyLogoAttribute($logoFile)
    {
        $path = 'uploads/companies/logos/';
        $logo = uploadFile($logoFile, $path);

        $this->attributes['company_logo_path'] = $logo->path;
    }

    /**
     * Get company logo url
     * 
     * @return string  $url
     */
    public function getCompanyLogoUrlAttribute()
    {
        $path = $this->attributes['company_logo_path'];
        return Storage::url($path);
    }

    /**
     * Get company ID in other way
     * 
     * @return string $id
     */
    public function getCompanyIdAttribute()
    {
        return $this->attributes['id'];
    }

    /**
     * Set company ID in other way
     * 
     * @return void
     */
    public function setCompanyIdAttribute(string $id)
    {
        $this->attributes['id'] = $id;
    }
}