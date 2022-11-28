<?php

namespace App\Models\Address;

use App\Enums\Address\AddressType;
use App\Observers\AddressObserver as Observer;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\MorphTo;


class Address extends Model
{

    use HasFactory;

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
    public $searchableFields = [
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
        'latitude',
        'longitude',
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
        self::observe(Observer::class);
    }

    /**
     * Define the searchable query
     *
     * @param Builder $query
     * @return Builder
     */
    protected function makeAllSearchableUsing(Builder $query)
    {
        return $query->with('addressable');
    }

    /**
     * Get addressable model attached to this address
     *
     * @return MorphTo
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

    /**
     * Create callable attribute of "route_xl_callable_address" only for RouteXL
     * and get address type description
     *
     * @return string
     */
    public function getRouteXlCallableAddressAttribute()
    {
        $address =
            $this->attributes['address'] .
            ', ' .
            $this->attributes['city'] .
            ', ' .
            $this->attributes['province'];

        return $address;
    }
}
