<?php

namespace App\Models\PaymentPickup;

use App\Models\Builderer;
use App\Models\Invoice\Invoice;
use App\Models\Revenue\Revenue;
use Illuminate\Database\Eloquent\{Builder, Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};
use Webpatser\Uuid\Uuid;


class PaymentPickupable extends Model
{

    use HasFactory;
    use SoftDeletes;


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
    public $searchableFields = [];

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
     * Possible morph types list
     *
     * @var  array
     */
    const MORPHED_TYPES = [Revenue::class, Invoice::class, PaymentTerm::class];

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
     * Create callable function of `wherePaymentPickup(PaymentPickup $pickup)`
     * This callable function will query only the result of payment pickup's pivot
     *
     * @param Builderer  $query
     * @param PaymentPickup  $pickup
     * @return Builder
     */
    public function scopeWherePaymentPickup(
        Builder $query,
        PaymentPickup $pickup
    ) {
        return $query->where('payment_pickup_id', $pickup->id);
    }

    /**
     * Create callable function of `wherePickupable($pickupable)`
     * This callable function only query the result where `payment_pickupable_type`
     * and `payment_pickupable_id` are match with the supplied argument.
     *
     * @param Builder  $query
     * @param mixed  $pickupable
     * @return Builder
     */
    public function scopeWherePickupable(Builder $query, $pickupable)
    {
        return $query
            ->where('payment_pickupable_id', $pickupable->id)
            ->where('payment_pickupable_type', get_class($pickupable));
    }

    /**
     * Get payment pickup
     *
     * @return BelongsTo
     */
    public function paymentPickup()
    {
        return $this->belongsTo(PaymentPickup::class);
    }

    /**
     * Get payment pickupable
     * Possible pickupable types:
     *  \App\Models\Revenue
     *  \App\Models\Invoice
     *  \App\Models\PaymentTerm
     *
     * @return MorphTo
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
     * @param string $clue
     * @return string
     */
    public static function guessType(string $clue)
    {
        // Convert clue to lower case
        $clue = strtolower($clue);

        switch ($clue) {
            case 'revenue':
                return Revenue::class;
                break;

            case 'invoice':
                return Invoice::class;
                break;

            case 'payment_term':
                return PaymentTerm::class;
                break;

            case 'paymentterm':
                return PaymentTerm::class;
                break;

            case Revenue::class:
                return Revenue::class;
                break;

            case Invoice::class:
                return Invoice::class;
                break;

            case PaymentTerm::class:
                return PaymentTerm::class;
                break;

            default:
                return Revenue::class;
                break;
        }
    }
}
