<?php

namespace App\Models\Owner;

use App\Models\Address\Address;
use App\Models\Company\Company;
use App\Models\User\User;
use App\Observers\OwnerObserver as Observer;
use App\Traits\ModelMutators;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphOne};

class Owner extends Model
{
    use HasFactory;
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
    public $searchableFields = [];
    /**
     * Define the searchable relations
     *
     * @var array
     */
    public array $searchableRelations = [
        'user' => [
            'fullname', 'email', 'phone'
        ]
    ];
    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'owners';
    /**
     * The primary key of the model
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * Relationship that will be loaded whenever the model loaded
     *
     * @var array
     */
    protected $with = ['user', 'address'];
    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'is_prime_owner',
    ];

    /**
     * Set which attribute that should be casted
     *
     * @var array
     */
    protected $casts = [
        'is_prime_owner' => 'boolean',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @static
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::observe(Observer::class);
    }

    /**
     * Modify the query used to retrieve models when making all of the models searchable.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function makeAllSearchableUsing(Builder $query)
    {
        return $query->with('user');
    }

    /**
     * Create callable method of primeOnly()
     * This callable method will query only
     * owner that's assigned as prime owner
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopePrimeOnly(Builder $query): Builder
    {
        return $query->where('is_prime_owner', true);
    }

    /**
     * Create callable method of nonPrime()
     * This callable method will query only non-prime owner
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeNonPrime(Builder $query): Builder
    {
        return $query->where('is_prime_owner', false);
    }

    /**
     * Get address of the owner
     *
     * @return MorphOne
     */
    public function address(): MorphOne
    {
        return $this->morphOne(Address::class, 'addressable');
    }

    /**
     * Get owner user model
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Company of the owner
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Check whether current owner is main owner.
     *
     * @return bool
     */
    public function isMainOwner(): bool
    {
        return boolval($this->is_prime_owner);
    }
}
