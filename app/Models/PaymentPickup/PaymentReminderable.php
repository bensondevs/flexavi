<?php

namespace App\Models\PaymentPickup;

use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, MorphTo};
use Webpatser\Uuid\Uuid;


class PaymentReminderable extends Model
{

    use HasFactory;
    use SoftDeletes;


    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'payment_reminderables';

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
    protected $fillable = ['payment_reminder_id', 'reminderable'];

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
        self::creating(function ($paymentReminderable) {
            $paymentReminderable->id = Uuid::generate()->string;
        });
    }

    /**
     * Get payment reminder of the reminderable
     *
     * @return BelongsTo
     */
    public function paymentReminder()
    {
        return $this->belongsTo(PaymentReminder::class);
    }

    /**
     * Get morphed model connected by this pivot
     *
     * @return MorphTo
     */
    public function reminderable()
    {
        return $this->morphTo();
    }
}
