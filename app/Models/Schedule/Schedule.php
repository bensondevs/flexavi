<?php

namespace App\Models\Schedule;

use App\Models\Company\Company;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Webpatser\Uuid\Uuid;


class Schedule extends Model
{

    use SoftDeletes;


    /**
     * Define the model table
     *
     * @var string
     */
    protected $table = 'schedules';

    /**
     * Define the model primary key column
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Define the timestamp option in model
     *
     * @var boolean
     */
    public $timestamps = true;

    /**
     * Define the model id incremental type
     *
     * @var boolean
     */
    public $incrementing = false;

    /**
     * Define the model fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'activity_name',
        'start',
        'end',
        'include_weekend',
        'start_money',
    ];

    /**
     * Define the model searchable attributes
     *
     * @var array
     */
    public $searchableFields = ['activity_name'];

    /**
     * Boot the model
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($schedule) {
            $schedule->id = Uuid::generate()->string;
        });
    }

    /**
     * Define the company relation
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
