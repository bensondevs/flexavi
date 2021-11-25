<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

use App\Enums\Analytic\AnalyticType as Type;

class Analytic extends Model
{
    use HasFactory;
    use Searchable;

    /**
     * Database table name
     * 
     * @var string
     */
    protected $table = 'analytics';

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
        //
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'company_id',
        'analytic_type',
        'start',
        'end',
        'analysis_result',
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

    	self::creating(function ($analytic) {
            $analytic->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create callable static "revenueTrends()" method
     * This callable statis method will query only "analytic_type"
     * that has value of AnalyticType::RevenueTrends
     * 
     * @param Illuminate\Database\Eloquent\Builder  $query
     * @return Illuminate\Database\Eloquent\Builder
     */
    public function scopeRevenueTrends(Builder $query)
    {
        return $query->where('analytic_type', Type::RevenueTrends);
    }

    /**
     * Create settable attribute of "analysis_result"
     * This settable attribute allow set using array and put as json
     * 
     * @param array  $analysisResult
     * @return void
     */
    public function setAnalysisResultAttribute(array $analysisResult)
    {
        $this->attributes['analysis_result'] = json_encode($analysis_result);
    }

    /**
     * Create callable attribute of "analysis_result"
     * This callabla attribute will return as array
     * 
     * @return array
     */
    public function getAnalysisResultAttribute()
    {
        $resultJson = $this->attributes['analysis_result'];
        return json_decode($resultJson, true);
    }
}