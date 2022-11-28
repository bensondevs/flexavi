<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Repat\LaravelJobs\Job;

class Jobable extends Model
{
    use HasFactory;

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
     * The table name
     *
     * @var string
     */
    protected $table = 'jobable';

    /**
     * The primary key of the model
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'jobable_id',
        'jobable_type',
        'job_id',
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
        self::creating(function ($jobable) {
            $jobable->id = generateUuid();
        });
        self::deleted(function ($jobable) {
            // delete the queue job
            Job::query()->where('id', $jobable->job_id)->delete();
        });
    }

    /**
     * Get jobable model attached to this job
     *
     * @return MorphTo
     */
    public function jobable()
    {
        return $this->morphTo();
    }
}
