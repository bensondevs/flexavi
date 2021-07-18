<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Laravel\Sanctum\HasApiTokens;
use Webpatser\Uuid\Uuid;

class Customer extends Model
{
    use HasApiTokens;
    use SoftDeletes;

    protected $table = 'customers';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',
        
        'fullname',
        'email',
        'phone',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($customer) {
            $customer->id = Uuid::generate()->string;
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

    public function getSalutationLabelAttribute()
    {
        $salutations = collect(self::CUSTOMER_SALUTATIONS);

        $salutations = $salutations->where('value', $this->attributes['salutation']);
        $salutation = $salutations->first();

        return $salutation['label'];
    }

    public static function salutationValues()
    {
        $salutations = collect(self::CUSTOMER_SALUTATIONS);
        $values = $salutations->pluck('value');
        
        return $values->toArray();
    }
}