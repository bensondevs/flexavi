<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class PaymentPickupable extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    /**
     * Database table name
     * 
     * @var string
     */
    protected $table = 'payment_pickupables';

    /**
     * Table name primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Set timestamp each time model is saved
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Set whether primary key use incrementing value or not
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
        //
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'payment_pickup_id',
        'payment_pickupable_type',
        'payment_pickupable_id',
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

    	self::creating(function ($paymentPickupable) {
            $paymentPickupable->id = Uuid::generate()->string;
    	});
    }

    /**
     * Get payment pickup
     */
    public function paymentPickup()
    {
        return $this->belongsTo(PaymentPickup::class);
    }

    /**
     * Get payment pickupable
     * 
     * Possible pickupable types:
     *  \App\Models\Revenue
     *  \App\Models\Invoice
     *  \App\Models\PaymentTerm
     */
    public function pickupable()
    {
        return $this->morphTo();
    }

    /**
     * Convert any clue to certain class.
     * Class found can be used as value for 
     * `payment_pickupable_type`
     * 
     * @param string  $clue
     */
    public static function guessType(string $clue)
    {
        // Convert clue to lower case
        $clue = strtolower($clue);

        if ($clue == 'revenue') {
            //
        }
    }
}