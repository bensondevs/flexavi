<?php

namespace App\Models\Warranty;

use App\Models\BelongsTo;
use App\Models\ExecuteWork\WorkWarranty;
use App\Observers\WarrantyAppointmentWorkObserver as Observer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarrantyAppointmentWork extends Model
{
    use HasFactory;

    protected $with = ['work'];

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'warranty_appointment_works';

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
    public $searchableFields = [];

    /**
     * Set which columns are mass fillable
     *
     * @var bool
     */
    protected $fillable = [
        'warranty_appointment_id',
        'work_warranty_id',
        'company_paid',
        'customer_paid'
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
     * Get warranty appointment
     *
     * @return BelongsTo
     */
    public function warrantyAppointment()
    {
        return $this->belongsTo(WarrantyAppointment::class);
    }

    /**
     * Get warranty work
     *
     * @return BelongsTo
     */
    public function work()
    {
        return $this->belongsTo(WorkWarranty::class, 'work_warranty_id', 'id');
    }
}
