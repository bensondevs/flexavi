<?php

namespace App\Models\ExecuteWork;

use App\Enums\ExecuteWorkRelatedMaterial\RelatedMaterialStatus;
use App\Models\BelongsTo;
use App\Models\Invoice\Invoice;
use App\Models\Quotation\Quotation;
use App\Observers\ExecuteWorkRelatedMaterialObserver as Observer;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ExecuteWorkRelatedMaterial extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * Autoload relation
     *
     * @var array
     */
    protected $with = ['invoice', 'quotation'];

    /**
     * The table name
     *
     * @var string
     */
    protected $table = 'execute_work_related_materials';

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
    public $searchableFields = [];

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = [
        'execute_work_id',
        'related_quotation',
        'related_invoice',
        'related_work_contract',
        'quotation_id',
        'invoice_id',
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

    /**
     * Create callable attribute of "related_quotation_description"
     * This callable attribute will get the description of related quotation
     *
     * @return string
     */
    public function getRelatedQuotationDescriptionAttribute()
    {
        $relatedQuotation = $this->attributes['related_quotation'];
        return RelatedMaterialStatus::getDescription($relatedQuotation);
    }

    /**
     * Create callable attribute of "related_invoice_description"
     * This callable attribute will get the description of related invoice
     *
     * @return string
     */
    public function getRelatedInvoiceDescriptionAttribute()
    {
        $relatedInvoice = $this->attributes['related_invoice'];
        return RelatedMaterialStatus::getDescription($relatedInvoice);
    }

    /**
     * Create callable attribute of "related_work_contract_description"
     * This callable attribute will get the description of related work contract
     *
     * @return string
     */
    public function getRelatedWorkContractDescriptionAttribute()
    {
        $relatedWorkContract = $this->attributes['related_work_contract'];
        return RelatedMaterialStatus::getDescription($relatedWorkContract);
    }

    /**
     * Create callable attribute of "quotation_file_url"
     * This callable attribute will get the quotation file url
     *
     * @return string
     */
    public function getQuotationFileUrlAttribute()
    {
        $quotationFile = $this->getFirstMedia('quotation_file');
        return $quotationFile ? $quotationFile->getFullUrl() : null;
    }

    /**
     * Create callable attribute of "invoice_file_url"
     * This callable attribute will get the invoice file url
     *
     * @return string
     */
    public function getInvoiceFileUrlAttribute()
    {
        $invoiceFile = $this->getFirstMedia('invoice_file');
        return $invoiceFile ? $invoiceFile->getFullUrl() : null;
    }

    /**
     * Create callable attribute of "work_contract_file_url"
     * This callable attribute will get the work contract file url
     *
     * @return string
     */
    public function getWorkContractFileUrlAttribute()
    {
        $workContractFile = $this->getFirstMedia('work_contract_file');
        return $workContractFile ? $workContractFile->getFullUrl() : null;
    }

    /**
     * Get execute work
     *
     * @return BelongsTo
     */
    public function executeWork()
    {
        return $this->belongsTo(ExecuteWork::class);
    }

    /**
     * Get invoice
     *
     * @return BelongsTo
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Get quotation
     *
     * @return BelongsTo
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}
