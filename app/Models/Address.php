<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Enums\Address\AddressType;

class Address extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'addresses';

    /**
     * Table name primary key
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
        'house_number',
        'house_number_suffix',
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

    	self::creating(function ($address) {
            $address->id = Uuid::generate()->string;
    	});
    }

    /**
     * Get addressable model attached to this address
     */
    public function addressable()
    {
        return $this->morphTo();
    }

    /**
     * Collect all possible types for address
     * 
     * @return array
     */
    public static function collectAllTypes()
    {
        return AddressType::asSelectArray();
    }

    /**
     * Create callable attribute of "address_type_description" 
     * and get address type description
     * 
     * @return string
     */
    public function getAddressTypeDescriptionAttribute()
    {
        $type = $this->attributes['address_type'];
        if ($type === AddressType::Other) {
            return $this->attributes['other_address_type_description'];
        }

        return AddressType::getDescription($type);
    }
}