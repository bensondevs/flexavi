<?php

namespace App\Models\Analytic;

use App\Enums\Analytic\AnalyticType as Type;
use Illuminate\Database\Eloquent\{Builder, Model};
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Webpatser\Uuid\Uuid;

class Analytic extends Model
{

    use HasFactory;


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
    public $searchableFields = [];

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
     * Create callable static "revenueGraph()" method
     * This callable statis method will query only "analytic_type"
     * that has value of AnalyticType::RevenueTrends
     *
     * @param Builder  $query
     * @return Builder
     */
    public function scopeRevenueGraph(Builder $query)
    {
        return $query->where('analytic_type', Type::RevenueGraph);
    }

    /**
     * Create callable static "costGraph()" method
     * This callable statis method will query only "analytic_type"
     * that has value of AnalyticType::costGraph
     *
     * @param Builder  $query
     * @return Builder
     */
    public function scopeCostGraph(Builder $query)
    {
        return $query->where('analytic_type', Type::CostGraph);
    }

    /**
     * Create callable static "profitGraph()" method
     * This callable statis method will query only "analytic_type"
     * that has value of AnalyticType::profitGraph
     *
     * @param Builder  $query
     * @return Builder
     */
    public function scopeProfitGraph(Builder $query)
    {
        return $query->where('analytic_type', Type::ProfitGraph);
    }

    /**
     * Create callable static "warrantiesPerRoofer()" method
     * This callable statis method will query only "analytic_type"
     * that has value of AnalyticType::WarrantiesPerRoofer
     *
     * @param Builder  $query
     * @return Builder
     */
    public function scopeWarrantiesPerRoofer(Builder $query)
    {
        return $query->where('analytic_type', Type::WarrantyPerRoofer);
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
        $this->attributes['analysis_result'] = json_encode($analysisResult);
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
