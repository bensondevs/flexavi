<?php

namespace App\Models\Company;

use App\Enums\Address\AddressType;
use App\Enums\Subscription\SubscriptionStatus;
use App\Models\{Address\Address,
    Appointment\Appointment,
    Appointment\SubAppointment,
    Car\Car,
    Car\CarRegisterTime,
    Cost\Cost,
    Customer\Customer,
    Employee\Employee,
    Inspection\Inspection,
    Invoice\Invoice,
    Owner\Owner,
    Quotation\Quotation,
    Schedule\Schedule,
    WorkContract\WorkContract,
    Workday\Workday,
    Worklist\Worklist
};
use App\Observers\CompanyObserver;
use App\Rules\Helpers\Media;
use App\Traits\ModelMutators;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{HasMany, MorphMany, MorphOne};
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Cashier\Billable;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class Company extends Model implements HasMedia
{
    use HasFactory;
    use SoftDeletes;
    use InteractsWithMedia;
    use CascadeSoftDeletes;
    use ModelMutators;
    use Billable;


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
    public array $searchableFields = [
        'company_name',
        'email',
        'phone_number',
        'vat_number',
        'commerce_chamber_number',
        'company_website_url',
    ];

    /**
     * Related tables that will be soft-deleted in model soft-delete
     *
     * @var array
     */
    protected array $cascadeDeletes = [
        'owners',
        'employees',
        'customers',
        'appointments',
        'quotations',
        'workContracts',
        'inspections',
        'invoices',
        'cars',
        'schedules',
        'workdays',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'trial_ends_at' => 'datetime',
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
     * The key type of the model
     *
     * @var string
     */
    protected $keyType = 'string';

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
        'company_website_url',
        'will_be_deleted_at',
        'will_be_permanently_deleted_at',
        'mollie_customer_id',
        'mollie_mandate_id',
        'tax_percentage',
        'trial_ends_at',
        'extra_billing_information',
    ];

    /**
     * Set the default image placeholder file
     *
     * @return string
     */
    public static function placeholder(): string
    {
        $placeholderFilename = 'placeholder-company.webp';
        $filename = Media::randomCustomFilename(
            explode('.', $placeholderFilename)[1]
        );
        Storage::copy(
            $placeholderFilename,
            "public/companies/$filename"
        );

        return $filename;
    }

    /**
     * Perform any actions required before the model boots.
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::observe(CompanyObserver::class);
    }

    /**
     * Set the mollie customer fields
     *
     * @return array
     */
    public function mollieCustomerFields(): array
    {
        return [
            'email' => $this->email,
            'name' => $this->name,
        ];
    }

    /**
     * Create callable method of "activeSubscription()"
     * This callable method will query only companies
     * which is active subscription
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeActiveSubscription(Builder $query): Builder
    {
        return $query->whereHas('subscription', function ($q) {
            $q->where('status', SubscriptionStatus::Active);
        });
    }

    /**
     * Create callable method of "waitingDestroy()"
     * This callable method will query only companies
     * which is in progress to be destroyed
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeWaitingDestroy(Builder $query): Builder
    {
        return $query->whereNotNull('will_be_deleted_at');
    }

    /**
     * Get the company visiting addresses
     *
     * @return MorphOne
     */
    public function visitingAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')->where(
            'address_type',
            AddressType::VisitingAddress
        );
    }

    /**
     * Get the company invoicing addresses
     *
     * @return MorphOne
     */
    public function invoicingAddress(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable')->where(
            'address_type',
            AddressType::InvoicingAddress
        );
    }

    /**
     * Get the company employees
     *
     * @return HasMany
     */
    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class)->whereNotNull('user_id');
    }

    /**
     * Get the company customers
     *
     * @return HasMany
     */
    public function customers(): HasMany
    {
        return $this->hasMany(Customer::class);
    }

    /**
     * Get the company appointments
     *
     * @return HasMany
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get the company sub-appointments
     *
     * @return HasMany
     */
    public function subAppointments(): HasMany
    {
        return $this->hasMany(SubAppointment::class);
    }

    /**
     * Get the company quotations
     *
     * @return HasMany
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    /**
     * Get the company work contracts
     *
     * @return HasMany
     */
    public function workContracts(): HasMany
    {
        return $this->hasMany(WorkContract::class);
    }

    /**
     * Get the company inspections
     *
     * @return HasMany
     */
    public function inspections(): HasMany
    {
        return $this->hasMany(Inspection::class);
    }

    /**
     * Get the company invoices
     *
     * @return HasMany
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get the company cars
     *
     * @return HasMany
     */
    public function cars(): HasMany
    {
        return $this->hasMany(Car::class);
    }

    /**
     * Get the company car register times
     *
     * @return HasMany
     */
    public function carRegisterTimes(): HasMany
    {
        return $this->hasMany(CarRegisterTime::class);
    }

    /**
     * Get the company costs
     *
     * @return HasMany
     */
    public function costs(): HasMany
    {
        return $this->hasMany(Cost::class);
    }

    /**
     * Get the company schedules
     *
     * @return HasMany
     */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }

    /**
     * Get the company workdays
     *
     * @return HasMany
     */
    public function workdays(): HasMany
    {
        return $this->hasMany(Workday::class);
    }

    /**
     * Get the company worklists
     *
     * @return HasMany
     */
    public function worklists(): HasMany
    {
        return $this->hasMany(Worklist::class);
    }

    /**
     * Get the company mandate
     *
     * @return HasMany
     */
    public function mollieMandates(): HasMany
    {
        return $this->hasMany(MollieCompanyMandate::class);
    }

    /**
     * Get prime owner of the company
     *
     * @return Owner|null
     */
    public function getPrimeOwnerAttribute(): ?Owner
    {
        $owners = $this->owners();
        if ($this->relationLoaded('owners')) {
            $owners = $this->owners;
        }

        return $owners->where('is_prime_owner', true)->first();
    }

    /**
     * Get the company owners
     *
     * @return HasMany
     */
    public function owners(): HasMany
    {
        return $this->hasMany(Owner::class)->whereNotNull('user_id');
    }

    /**
     * Set visiting address of company
     *
     * @param array $data
     * @return void
     */
    public function setVisitingAddressAttribute(array $data): void
    {
        if ($address = $this->getVisitingAddressAttribute()) {
            $address->delete();
        }
        $address = new Address([
            'address_type' => AddressType::VisitingAddress,
            'addressable_id' => $this->attributes['id'],
            'addressable_type' => self::class,
            'address' => $data['address'],
            'house_number' => $data['house_number'],
            'house_number_suffix' => $data['house_number_suffix'],
            'zipcode' => $data['zipcode'],
            'city' => $data['city'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
        ]);
        $address->save();
    }

    /**
     * Get visiting address of company
     *
     * @return Address|null
     */
    public function getVisitingAddressAttribute(): ?Address
    {
        $addresses = $this->relationLoaded('addresses')
            ? $this->addresses
            : $this->addresses();
        $type = AddressType::VisitingAddress;

        return $addresses->where('address_type', $type)->first();
    }

    /**
     * Get the company addresses
     *
     * @return MorphMany
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Set invoicing address of company
     *
     * @param array $data
     * @return void
     */
    public function setInvoicingAddressAttribute(array $data): void
    {
        if ($address = $this->getInvoicingAddressAttribute()) {
            $address->delete();
        }
        $address = new Address([
            'address_type' => AddressType::InvoicingAddress,
            'addressable_id' => $this->attributes['id'],
            'addressable_type' => self::class,
            'address' => $data['address'],
            'house_number' => $data['house_number'],
            'house_number_suffix' => $data['house_number_suffix'],
            'zipcode' => $data['zipcode'],
            'city' => $data['city'],
            'province' => $data['province'],
            'latitude' => $data['latitude'] ?? null,
            'longitude' => $data['longitude'] ?? null,
        ]);
        $address->save();
    }

    /**
     * Get invoicing address of company
     *
     * @return Address|null
     */
    public function getInvoicingAddressAttribute(): ?Address
    {
        $addresses = $this->relationLoaded('addresses')
            ? $this->addresses
            : $this->addresses();
        $type = AddressType::InvoicingAddress;

        return $addresses->where('address_type', $type)->first();
    }

    /**
     * Set signature pdf/image
     *
     * @param UploadedFile $signature
     * @return static
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setSignature(UploadedFile $signature): static
    {
        $this->clearMediaCollection("signature");
        $this->addMedia($signature)
            ->usingFileName(randomString(10) . "." . $signature->extension())
            ->toMediaCollection("signature", "public");
        return $this;
    }

    /**
     * Create settable attribute of "logo"
     *
     * @param UploadedFile $logo
     * @return void
     * @throws FileDoesNotExist
     * @throws FileIsTooBig
     */
    public function setLogoAttribute(UploadedFile $logo): void
    {
        $collectionName = "logo";
        if ($this->getFirstMediaUrl($collectionName)) {
            $this->clearMediaCollection($collectionName);
        }

        $this->addMedia($logo)
            ->addCustomHeaders([
                'ACL' => 'public-read'
            ])
            ->toMediaCollection($collectionName, 'do');
    }

    /**
     * Create accessor attribute of "logo"
     *
     * @return string|null
     */
    public function getLogoUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl("logo") ?? null;
    }

    /**
     * Get company ID in other way
     *
     * @return string
     */
    public function getCompanyIdAttribute(): string
    {
        return $this->attributes['id'];
    }

    /**
     * Set company ID in other way
     *
     * @param string $id
     * @return void
     */
    public function setCompanyIdAttribute(string $id): void
    {
        $this->attributes['id'] = $id;
    }

    /**
     * Set company vat attribute
     *
     * @param $value
     * @return void
     */
    public function setVatNumberAttribute($value): void
    {
        $this->attributes['vat_number'] = str_replace('-', '', $value);
    }

    /**
     * Set the destory days duration of the company
     *
     * @param int $days
     * @return bool
     */
    public function willBeDestroyedInDays(int $days): bool
    {
        $destroyTime = now()
            ->copy()
            ->addDays($days);
        $this->attributes['will_be_permanently_deleted_at'] = $destroyTime;

        return $this->save();
    }

    /**
     * Check company has active subscription or not
     *
     * @return bool
     */
    public function hasActiveSubscription(): bool
    {
        return $this->onGenericTrial() || $this->subscribed('main');
    }

    /**
     * @return string
     * @link https://docs.mollie.com/reference/v2/payments-api/create-payment#parameters
     * @example 'nl_NL'
     */
    public function getLocale(): string
    {
        return config('app.locale') === "nl"
            ? "nl_NL" : "en_US";
    }

    /**
     * Check company if eligible for trial subscription
     *
     * @return bool
     */
    public function isEligibleForTrial(): bool
    {
        return is_null($this->trial_ends_at);
    }

    /**
     * Start trial subscription
     *
     * @return void
     */
    public function startTrial(): void
    {
        $this->attributes['trial_ends_at'] = now()->addDays(14);
        $this->saveQuietly();
    }

    /**
     * Check company is subscribed to a plan
     *
     * @param string $plan
     * @return bool
     */
    public function isSubscribed(string $plan): bool
    {
        return $this->subscribedToPlan($plan, 'main');
    }

    /**
     * Define the searchable query
     *
     * @param Builder $query
     * @return Builder
     */
    protected function makeAllSearchableUsing(Builder $query): Builder
    {
        return $query->with('owners')->with('employees');
    }
}
