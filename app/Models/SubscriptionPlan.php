<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\{ Model, SoftDeletes, Builder };
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Observers\SubscriptionPlanObserver as Observer;

class SubscriptionPlan extends Model
{
    use HasFactory, SoftDeletes, Searchable;

    /**
     * Database table name
     * 
     * @var string
     */
    protected $table = 'subscription_plans';

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
    protected $searchable = [
        'plan_name',
        'plan_description',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'plan_name',
        'plan_description',
        'price',
        'duration_days',
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
}