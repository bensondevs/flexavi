<?php

namespace App\Models\Warranty;

use App\Enums\Warranty\WarrantyStatus;
use App\Models\Appointment\Appointment;
use App\Models\Work\Work;
use App\Observers\WarrantyWorkObserver as Observer;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Znck\Eloquent\Traits\BelongsToThrough;

class WarrantyWork extends Model
{

    use BelongsToThrough;
    use HasFactory;
    use SoftDeletes;


    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'warranty_works';

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
        'warranty_id',
        'work_id',
        'status',
        'amount',
        'note',
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
     * Create callable attribute of "status_description"
     * This callable attribute will return status enum description
     *
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];

        return WarrantyStatus::getDescription($status);
    }

    /**
     * Create callable attribute of "formatted_amount"
     * This callable attribute will return amount in formatted form
     *
     * @return string
     */
    public function getFormattedAmountAttribute()
    {
        $amount = $this->attributes['amount'];

        return currency_format($amount);
    }

    /**
     * Get the appointment parent of the warranty work
     *
     * @return \Znck\Eloquent\Relations\BelongsToThrough
     */
    public function appointment()
    {
        return $this->belongsToThrough(Appointment::class, Warranty::class);
    }

    /**
     * Get the warranty parent of the current warranty work
     *
     * @return BelongsTo
     */
    public function warranty()
    {
        return $this->belongsTo(Warranty::class);
    }

    /**
     * Get the work connected using this pivor
     *
     * @return BelongsTo
     */
    public function work()
    {
        return $this->belongsTo(Work::class);
    }
}
