<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ 
    Model, 
    SoftDeletes, 
    Builder, 
    Factories\HasFactory 
};
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class PaymentReminderable extends Model
{
    use HasFactory, SoftDeletes, Searchable;

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
    protected $searchable = [
        //
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'payment_reminder_id',
        'reminderable',
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

    	self::creating(function ($paymentReminderable) {
            $paymentReminderable->id = Uuid::generate()->string;
    	});
    }

    /**
     * Get payment reminder of the reminderable
     */
    public function paymentReminder()
    {
        return $this->belongsTo(PaymentReminder::class);
    }

    /**
     * Get morphed model connected by this pivot
     */
    public function reminderable()
    {
        return $this->morphTo();
    }
}