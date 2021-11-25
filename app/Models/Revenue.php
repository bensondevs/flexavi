<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Revenue extends Model
{
    use HasFactory;
    use SoftDeletes;
    use Searchable;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'revenues';

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
        'revenue_name',
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',

        'revenueable_type',
        'revenueable_id',

        'revenue_name',
        'amount',
        'paid_amount',
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

    	self::creating(function ($revenue) {
            $revenue->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create callable static "createdBetween" method
     * This callable method will query only revenue that's 
     * created between certain range of time
     * 
     * @param \Illuminate\Database\Eloquent\Builder  $query
     * @param mixed  $start
     * @param mixed  $end
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCreatedBetween(Builder $query, $start, $end)
    {
        return $query->where('created_at', '>=', $start)
            ->where('created_at', '<=', $end);
    }

    /**
     * Create callable "unpaid_amount" attribute.
     * This callable attribute will return unpaid amount of revenue
     * 
     * @return double
     */
    public function getUnpaidAmountAttribute()
    {
        $amount = $this->attributes['amount'];
        $paid = $this->attributes['paid_amount'];

        return $amount - $paid;
    }

    /**
     * Create callable "is_settled" attribute.
     * This callable attribute will return boolean status of revenue settlement
     * 
     * @return bool
     */
    public function getIsSettledAttribute()
    {
        return ($this->getUnpaidCostAttribute() <= 0);
    }

    /**
     * Get company that owns this revenue
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get revenueable laravel-model
     */
    public function revenueable()
    {
        return $this->morphTo();
    }

    /**
     * Get receipt of current revenue
     */
    public function receipt()
    {
        return $this->morphOne(Receipt::class);
    }
}