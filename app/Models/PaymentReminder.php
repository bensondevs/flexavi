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

class PaymentReminder extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    /**
     * Database table name
     * 
     * @var string
     */
    protected $table = 'payment_reminders';

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
        'company_id',
        'appointment_id',
        'reminded_amount',
        'transferred_amount',
        'reason_not_all',
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

    	self::creating(function ($paymentReminder) {
            $paymentReminder->id = Uuid::generate()->string;
    	});
    }

    /**
     * Get current company
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get appointment of the reminder
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}