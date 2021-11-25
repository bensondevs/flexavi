<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Workable extends Model
{
    use HasFactory;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'workables';

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
        'workable_type',
        'workable_id',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'workable_type',
        'workable_id',
    ];

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

    	self::creating(function ($workable) {
            $workable->id = Uuid::generate()->string;
    	});
    }

    /**
     * Check if a work is already attached to certain workable model
     * 
     * @static
     * @param \App\Models\Work  $work
     * @param mixed  $workable
     * @return bool
     */
    public static function isAlreadyAttached(Work $work, $workable)
    {
        return self::where('work_id', $work->id)
            ->where('workable_type', get_class($workable))
            ->where('workable_id', $workable->id)
            ->exists();
    }
}