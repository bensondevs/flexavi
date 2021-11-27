<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentPickup extends Model
{
    use HasFactory;
    use Searchable;
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
        'appointment_id',
        'employee_id',
        'should_pickup_amount',
        'picked_up_amount',
        'reason_not_all',
        'should_picked_up_at',
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

    	self::creating(function ($paymentPickup) {
            $paymentPickup->id = Uuid::generate()->string;
    	});
    }

    /**
     * Get appointment of payment pickup happen
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get employee who do the payment pick up
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get all revenues that'll be picked up
     */
    public function revenues()
    {
        return $this->hasManyThrough(Revenue::class, PaymentPickupRevenue::class);
    }

    /**
     * Get all revenues pivot
     */
    public function revenuePivots()
    {
        return $this->hasMany(PaymentPickupRevenue::class);
    }
}