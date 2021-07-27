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

use App\Observers\CustomerObserver;

class Customer extends Authenticatable
{
    use Notifiable;
    use HasApiTokens;
    use SoftDeletes;
    use Searchable;

    protected $guard = 'customer_api';

    protected $table = 'customers';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'fullname',
        'email',
        'phone',

        'address',
        'zipcode',
        'city',
        'province',
    ];

    protected $fillable = [
        'company_id',
        
        'fullname',
        'email',
        'phone',

        'address',
        'house_number',
        'house_number_suffix',
        'zipcode',
        'city',
        'province',
    ];

    protected static function boot()
    {
    	parent::boot();
        self::observe(CustomerObserver::class);

    	self::creating(function ($customer) {
            $customer->id = Uuid::generate()->string;
            $customer->unique_key = $customer->generateUniqueKey();
    	});
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function addresses()
    {
        return $this->hasManyThrough(
            Address::class, 
            User::class,
            'id',
            'user_id',
            'id',
            'id'
        );
    }

    public function generateUniqueKey()
    {
        return $this->attributes['unique_key'] = random_string(5);
    }

    public static function findUsingCredentials(array $credentials)
    {
        return Customer::where('zipcode', $credentials['zipcode'])
            ->where('house_number', $credentials['house_number'])
            ->firstOrFail();
    }

    public function attemptAutenticate(string $uniqueKey)
    {
        if ($this->attributes['unique_key'] !== $uniqueKey) {
            return false;
        }

        $this->token = $this->createToken(time())->plainTextToken;
        return $this;
    }
}