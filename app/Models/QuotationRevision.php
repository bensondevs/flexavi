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

    protected $table = 'quotation_revisions';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'quotation_id',
        'revision_requester_id',
    
        'is_applied',
        'applied_at',
    ];

    protected $casts = [
        'revisions' => 'array',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($revision) {
            $revision->id = Uuid::generate()->string;
    	});
    }

    public function setRevisionDataAttribute(array $revisionData)
    {
        $this->attributes['revision'] = json_encode($revisionData);
    }

    public function quotation()
    {
        return $this->belongsTo(
            'App\Models\Quotation', 
            'quotation_id', 
            'id'
        );
    }
}