<?php

namespace App\Models\Worklist;

use App\Models\User\User;
use App\Observers\WorklistEmployeeObserver as Observer;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorklistEmployee extends Model
{
    use HasFactory;

    use SoftDeletes;

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'worklist_employees';

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
    protected $fillable = ['worklist_id', 'user_id'];

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
     * Get worklist model
     *
     * @return BelongsTo
     */
    public function worklist()
    {
        return $this->belongsTo(Worklist::class);
    }

    /**
     * Get user model
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Assign many employee into worklist
     *
     * @param Worklist $worklist
     * @param array $employeeIds
     * @return bool
     */
    public static function attachMany(Worklist $worklist, $employeeIds)
    {
        $worklistEmployees = [];
        foreach ($employeeIds as $id) {
            array_push($worklistEmployees, [
                'id' => generateUuid(),
                'worklist_id' => $worklist->id,
                'user_id' => $id,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return self::insert($worklistEmployees);
    }
}
