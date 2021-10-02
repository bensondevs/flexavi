<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class Address extends Model
{
    use Searchable;
    use SoftDeletes;

    protected $table = 'addresses';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $searchable = [
        'house_number',
        'house_number_suffix',
        'zipcode',
        'city',
        'province',
    ];

    protected $fillable = [
        'addressable_type',
        'addressable_id',

        'address_type',
        'other_address_type_description',

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

    	self::creating(function ($address) {
            $address->id = Uuid::generate()->string;
    	});
    }

    public function addressable()
    {
        return $this->morphTo();
    }
}