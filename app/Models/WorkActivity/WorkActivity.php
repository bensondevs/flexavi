<?php

namespace App\Models\WorkActivity;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Webpatser\Uuid\Uuid;

class WorkActivity extends Model
{

    use HasFactory;

    /**
     * Define the model table
     *
     * @var string
     */
    protected $table = 'work_activities';

    /**
     * Define the model id column
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Define the model timestamp option
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
     * Define the model searchable attributes
     *
     * @var array
     */
    public $searchableFields = ['activity_name'];

    /**
     * Define the model fillable attributes
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'assignable_type',
        'assignable_id',
        'activity_name',
        'price',
        'unit',
    ];

    /**
     * Boot the model
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();
        self::creating(function ($workActivity) {
            $workActivity->id = Uuid::generate()->string;
        });
    }
}
