<?php

namespace App\Models\Customer;

use App\Enums\Customer\CustomerAcquisition;
use App\Enums\Customer\CustomerSalutation;
use App\Models\Address\Address;
use App\Models\Appointment\Appointment;
use App\Models\Company\Company;
use App\Models\Invoice\Invoice;
use App\Models\Quotation\Quotation;
use App\Models\WorkContract\WorkContract;
use App\Observers\CustomerObserver as Observer;
use App\Traits\ModelMutators;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, MorphMany, MorphOne};
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;
    use \App\Traits\Searchable;
    use ModelMutators;

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
    public array $searchableFields = ['fullname', 'email', 'phone'];
    /**
     * Customer guard service
     *
     * @var string
     */
    protected $guard = 'customer_api';

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'customers';

    /**
     * The primary key of the model
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'salutation',
        'fullname',
        'email',
        'phone',
        'second_phone',
        'acquired_through',
    ];

    /**
     * Find customer by credentials including "zipcode" and "house_number"
     *
     * @static
     * @param array $credentials
     * @return Customer
     */
    public static function findUsingCredentials(array $credentials): Customer
    {
        return Customer::where('zipcode', $credentials['zipcode'])
            ->where('house_number', $credentials['house_number'])
            ->firstOrFail();
    }

    /**
     * Collect all salutation types of customer
     *
     * @static
     * @return array
     */
    public static function collectAllSalutationTypes(): array
    {
        return CustomerSalutation::asSelectArray();
    }

    /**
     * Collect all acquisition types of customer
     *
     * @static
     * @return array
     */
    public static function collectAllAcquisitionTypes(): array
    {
        return CustomerAcquisition::asSelectArray();
    }

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::observe(Observer::class);
    }

    /**
     * Get company data
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get customer addresses
     *
     * @return MorphMany
     */
    public function addresses(): MorphMany
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Get customer address
     *
     * @return MorphOne
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * Get customer quotations
     *
     * @return HasMany
     */
    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }

    /**
     * Get customer invoices
     *
     * @return HasMany
     */
    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    /**
     * Get customer work contracts
     *
     * @return HasMany
     */
    public function workContracts(): HasMany
    {
        return $this->hasMany(WorkContract::class);
    }

    /**
     * Get customer appointments
     *
     * @return HasMany
     */
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }

    /**
     * Get Customer's notes
     *
     * @return HasMany
     */
    public function notes(): HasMany
    {
        return $this->hasMany(CustomerNote::class);
    }

    /**
     * Generate unique key for customer login
     *
     * @return string
     */
    public function generateUniqueKey(): string
    {
        return $this->attributes['unique_key'] = random_string(5);
    }

    /**
     * Attempt login to customer using unique key
     *
     * @param string $uniqueKey
     * @return Customer|false
     */
    public function attemptAuthenticate(string $uniqueKey): bool|static
    {
        if ($this->attributes['unique_key'] !== $uniqueKey) {
            return false;
        }
        $this->token = $this->createToken(time())->plainTextToken;

        return $this;
    }

    /**
     * Create callable attribute of "acquired_through_description"
     *
     * @return string
     */
    public function getAcquiredThroughDescriptionAttribute(): string
    {
        $acquiredThrough = $this->attributes['acquired_through'];
        $acquiredThrough = !is_int($acquiredThrough) ?
            ((int) $acquiredThrough) : $acquiredThrough;

        return CustomerAcquisition::getDescription($acquiredThrough);
    }

    /**
     * Create callable attribute of "salutation_description"
     *
     * @return string
     */
    public function getSalutationDescriptionAttribute(): string
    {
        $salutation = $this->attributes['salutation'];
        $salutation = !is_int($salutation) ?
            ((int) $salutation) : $salutation;

        return CustomerSalutation::getDescription($salutation);
    }
}
