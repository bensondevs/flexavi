<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class QuotationRevision extends Model
{
    use HasFactory;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'quotation_revisions';

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
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'quotation_id',
        'revision_requester_id',
    
        'is_applied',
        'applied_at',
    ];

    /**
     * Set which attribute that should be casted
     * 
     * @var array
     */
    protected $casts = [
        'revisions' => 'array',
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

    	self::creating(function ($revision) {
            $revision->id = Uuid::generate()->string;
    	});
    }

    /**
     * Create settable "revision_data" attribute
     * This settable attribute will allow insertion of 
     * revision data as json string in datbase.
     * 
     * @param array  $revisionData
     */
    public function setRevisionDataAttribute(array $revisionData)
    {
        $this->attributes['revision'] = json_encode($revisionData);
    }

    /**
     * Get target quotation revision
     */
    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }
}