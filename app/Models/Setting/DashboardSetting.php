<?php

namespace App\Models\Setting;

use App\Enums\Setting\DashboardSetting\DashboardInvoiceRevenueDateRange;
use App\Enums\Setting\DashboardSetting\DashboardResultGraph;
use App\Models\Company\Company;
use App\Traits\DefaultSetting;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Validation\Rule;

class DashboardSetting extends Model
{
    use DefaultSetting;

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
    public array $searchableFields = [];
    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'dashboard_settings';
    /**
     * Table name primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';
    /**
     * Set which columns are mass fillable
     *
     * @var bool
     */
    protected $fillable = [
        'company_id',
        'result_graph',
        'invoice_revenue_date_range',
        'best_selling_service_date_range'
    ];
    /**
     * Define auto appended attributes on model load
     *
     * @var bool
     */
    protected $appends = ['result_graph_description', 'invoice_revenue_date_range_description'];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot(): void
    {
        parent::boot();
        self::creating(function ($setting) {
            $setting->id = generateUuid();
        });
    }

    /**
     * Get company of the quotation
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

     /**
     * Create callable "result_graph_description" attribute
     * This callable attribute will return status enum description
     *
     * @return string
     */
    public function getResultGraphDescriptionAttribute()
    {
        return DashboardResultGraph::getDescription(
            $this->attributes['result_graph']
        );
    }

     /**
     * Create callable "invoice_revenue_date_range_description" attribute
     * This callable attribute will return status enum description
     *
     * @return string
     */
    public function getInvoiceRevenueDateRangeDescriptionAttribute()
    {
        return DashboardInvoiceRevenueDateRange::getDescription(
            $this->attributes['invoice_revenue_date_range']
        );
    }
}
