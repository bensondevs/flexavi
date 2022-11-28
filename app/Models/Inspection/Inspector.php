<?php

namespace App\Models\Inspection;

use App\Models\Employee\Employee;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webpatser\Uuid\Uuid;

class Inspector extends Model
{

    use HasFactory;

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'inspectors';

    /**
     * The primary key of the model
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
     * @var array
     */
    protected $fillable = ['inspection_id', 'employee_id'];

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
        self::creating(function ($inspector) {
            $inspector->id = Uuid::generate()->string;
        });
    }

    /**
     * Get employee who become inspector
     *
     * @return BelongsTo
     */
    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }

    /**
     * Get inspection model
     *
     * @return BelongsTo
     */
    public function inspection()
    {
        return $this->belongsTo(Inspection::class);
    }
}
