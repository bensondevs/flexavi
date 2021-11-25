<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Observers\CustomerObserver;

class Customer extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;
    use Searchable;

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
        'fullname',
        'email',
        'phone',

        'address',
        'zipcode',
        'city',
        'province',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',
        
        'fullname',
        'email',
        'phone',
        'second_phone',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();
        self::observe(CustomerObserver::class);

    	self::creating(function ($customer) {
            $customer->id = Uuid::generate()->string;
            $customer->unique_key = $customer->generateUniqueKey();
    	});
    }

    /**
     * Get company data
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get customer addresses
     */
    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    /**
     * Generate unique key for customer login
     * 
     * @return string
     */
    public function generateUniqueKey()
    {
        return $this->attributes['unique_key'] = random_string(5);
    }

    /**
     * Find customer by credentials including "zipcode" and "house_number"
     * 
     * @static
     * @param array  $credentials
     * @return \App\Models\Customer
     */
    public static function findUsingCredentials(array $credentials)
    {
        return Customer::where('zipcode', $credentials['zipcode'])
            ->where('house_number', $credentials['house_number'])
            ->firstOrFail();
    }

    /**
     * Attempt login to customer using unique key
     * 
     * @param string  $uniqueKey
     * @return \App\Models\Customer
     */
    public function attemptAutenticate(string $uniqueKey)
    {
        if ($this->attributes['unique_key'] !== $uniqueKey) {
            return false;
        }

        $this->token = $this->createToken(time())->plainTextToken;
        return $this;
    }
}