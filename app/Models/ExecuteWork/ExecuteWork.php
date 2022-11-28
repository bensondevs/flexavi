<?php

namespace App\Models\ExecuteWork;

use App\Enums\ExecuteWork\ExecuteWorkStatus;
use App\Enums\ExecuteWorkPhoto\PhotoConditionType;
use App\Models\Appointment\Appointment;
use App\Models\Company\Company;
use App\Models\Work\Work;
use App\Observers\ExecuteWorkObserver as Observer;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ExecuteWork extends Model implements HasMedia
{

    use HasFactory;

    use SoftDeletes;
    use InteractsWithMedia;

    /**
     * Autoload relation
     *
     * @var array
     */
    protected $with = ['relatedMaterial'];

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'execute_works';

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
     * Set which columns are searchable
     *
     * @var array
     */
    public $searchableFields = ['note', 'finish_note'];

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'appointment_id',
        'status',
        'note',
        'finish_note',
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
     * Create callable attribute "status_description"
     * To get the description of the execute work status
     *
     * @return string
     */
    public function getStatusDescriptionAttribute()
    {
        $status = $this->attributes['status'];

        return ExecuteWorkStatus::getDescription($status);
    }

    /**
     * Get execute work company
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get execute work related material
     *
     * @return BelongsTo
     */
    public function relatedMaterial()
    {
        return $this->hasOne(ExecuteWorkRelatedMaterial::class);
    }

    /**
     * Get appointment of the execute work company
     *
     * @return BelongsTo
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get works execute work
     *
     * @return BelongsTo
     */
    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    /**
     * Get previous execute work which is continued by current execute work
     *
     * @return BelongsTo
     */
    public function previousExecuteWork()
    {
        return $this->belongsTo(self::class, 'previous_execute_work_id');
    }

    /**
     * Execute work photos of this section
     *
     * @return HasMany
     */
    public function photos()
    {
        return $this->hasMany(ExecuteWorkPhoto::class);
    }

    /**
     * Get photo before the execute work
     *
     * @return HasMany
     */
    public function beforeWorkPhotos()
    {
        return $this->photos()->where(
            'photo_condition_type',
            PhotoConditionType::Before
        );
    }

    /**
     * Get the photo after the execute work
     *
     * @return HasMany
     */
    public function afterWorkPhotos()
    {
        return $this->photos()->where(
            'photo_condition_type',
            PhotoConditionType::After
        );
    }

    /**
     * Set the execute work as finished
     * The parameter of $finishData
     *
     * @param array $finishData
     * @return bool
     */
    public function finish(array $finishData)
    {
        $this->attributes['finish_note'] = isset($finishData['finish_note'])
            ? $finishData['finish_note']
            : null;
        $this->attributes['finished_at'] = now();
        $this->attributes['status'] = ExecuteWorkStatus::Finished;

        return $this->save();
    }
}
