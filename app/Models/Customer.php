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

    protected $table = 'customers';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    const CUSTOMER_SALUTATIONS = [
        [
            'label' => 'Mister',
            'value' => 'mr',
        ],
        [
            'label' => 'Mistress',
            'value' => 'mrs',
        ],
        [
            'label' => 'Miss',
            'value' => 'ms',
        ],
        [
            'label' => 'Sir',
            'value' => 'sir',
        ],
        [
            'label' => 'Madam',
            'value' => 'madam',
        ],
        [
            'label' => 'Dear',
            'value' => 'dear',
        ]
    ];

    protected $fillable = [
        'company_id',
        
        'fullname',
        'salutation',
        'address',
        'house_number',
        'zipcode',
        'city',
        'province',
        'email',
        'phone',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

        self::retrieved(function ($customer) {
            $customer->salutation = $customer->salutation_label;
        });

    	self::creating(function ($customer) {
            $customer->id = Uuid::generate()->string;
    	});
    }

    public function company()
    {
        return $this->belongsTo(
            'App\Models\Company', 
            'company_id', 
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