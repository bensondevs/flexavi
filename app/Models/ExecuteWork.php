<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use App\Enums\ExecuteWork\ExecuteWorkStatus;
use App\Enums\ExecuteWorkPhoto\PhotoConditionType;

use App\Observers\ExecuteWorkObserver;

class ExecuteWork extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

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
    protected $searchable = [
        'description',
        'note',
        'finish_note',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',
        'work_id',
        'appointment_id',
        'sub_appointment_id',
        'description',
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
        self::observe(ExecuteWorkObserver::class);

    	self::creating(function ($executeWork) {
            $executeWork->id = Uuid::generate()->string;
    	});
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
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get appointment of the execute work company
     */
    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    /**
     * Get works execute work
     */
    public function work()
    {
        return $this->belongsTo(Work::class);
    }

    /**
     * Get previous execute work which is continued by current execute work
     */
    public function previousExecuteWork()
    {
        return $this->belongsTo(self::class, 'previous_execute_work_id');
    }

    /**
     * Execute work photos of this section
     */
    public function photos()
    {
        return $this->hasMany(ExecuteWorkPhoto::class);
    }

    /**
     * Get photo before the execute work
     */
    public function beforeWorkPhotos()
    {
        return $this->photos()->where('photo_condition_type', PhotoConditionType::Before);
    }

    /**
     * Get the photo after the execute work
     */
    public function afterWorkPhotos()
    {
        return $this->photos()->where('photo_condition_type', PhotoConditionType::After);
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
        $this->attributes['finish_note'] = isset($finishData['finish_note']) ?
            $finishData['finish_note'] : null;
        $this->attributes['finished_at'] = now();
        $this->attributes['status'] = ExecuteWorkStatus::Finished;
        return $this->save();
    }
}