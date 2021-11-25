<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Owner extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

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
     * Relationship that will be loaded whenever the model loaded
     * 
     * @var array
     */
    protected $with = ['user', 'addresses'];

    /**
     * Set which columns are searchable
     * 
     * @var array
     */
    protected $searchable = [
        'bank_name',
        'bic_code',
        'bank_account',
        'bank_holder_name',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'user_id',
        'company_id',

        'is_prime_owner',

        'bank_name',
        'bic_code',
        'bank_account',
        'bank_holder_name',
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

    	self::creating(function ($owner) {
            $owner->id = Uuid::generate()->string;

            if (! $owner->company_id) $owner->is_prime_owner = true;
    	});
    }

    /**
     * Create callable method of primeOnly()
     * This callable method will query only 
     * owner that's assigned as prime owner
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePrimeOnly(Builder $query)
    {
        return $query->where('is_prime_owner', true);
    }

    /**
     * Create callable method of nonPrime()
     * This callable method will query only non-prime owner
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNonPrime(Builder $query)
    {
        return $query->where('is_prime_owner', false);
    }

    /**
     * Get addresses of the owner
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Get owner user model
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Company of the owner
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}