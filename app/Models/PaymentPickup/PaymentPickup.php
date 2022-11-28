<?php

namespace App\Models\PaymentPickup;

use App\Models\Appointment\Appointment;
use App\Models\Company\Company;
use App\Models\Invoice\Invoice;
use App\Observers\PaymentPickupObserver as Observer;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};


class PaymentPickup extends Model
{

    use HasFactory;
    use SoftDeletes;


    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'payment_pickups';

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
     * Set which columns are mass fillable
     *
     * @var bool
     */
    protected $fillable = [
        'company_id',
        'appointment_id',
        'should_pickup_amount',
        'picked_up_amount',
        'picked_up_at',
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
        self::observe(Observer::class);
    }

    /**
     * Get company of payment pickup
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get appointment where payment pickup happen
     *
     * @return BelongsTo
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get payment pickup items
     *
     * @return HasMany
     */
    public function items()
    {
        return $this->hasMany(PaymentPickupItem::class);
    }

    /**
     * Create callable "formatted_should_pickup_amount" attribute
     * This attribute will return should pickup amount
     * in currency formatted form.
     *
     * @return string
     */
    public function getFormattedShouldPickupAmountAttribute()
    {
        return currency_format($this->attributes['should_pickup_amount']);
    }

    /**
     * Create callable "formatted_picked_up_amount" attribute
     * This attribute will return pickup up amount
     * in currency formatted form.
     *
     * @return string
     */
    public function getFormattedPickedUpAmountAttribute()
    {
        return currency_format($this->attributes['picked_up_amount']);
    }


    /**
     * Insert payment pickup items
     *
     * @param array  $daya
     * @return void
     */
    public function insertPaymentPickupItems(array $data)
    {
        $rawItems = [];
        $shouldPickupAmount = 0;
        foreach ($data as $index => $row) {
            $paymentTermsId = $row['payment_term_ids'];

            $invoice = Invoice::find($row['invoice_id']);
            array_push($rawItems, [
                'id' => generateUuid(),
                'invoice_id' => $row['invoice_id'],
                'payment_pickup_id' => $this->attributes['id'],
                'total_bill' => $invoice->total_amount - $invoice->total_paid,
                'pickup_amount' => $row['pickup_amount'],
                'note' => $row['note'],
                'payment_term_ids' => json_encode($paymentTermsId),
                'created_at' => now(),
                'updated_at' => now(),
            ]);
            $shouldPickupAmount += ($invoice->total_amount - $invoice->total_paid);
        }

        PaymentPickupItem::insert($rawItems);

        $this->attributes['should_pickup_amount'] = $shouldPickupAmount;
        $this->saveQuietly();
    }
}
