<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class QuotationPhoto extends Model
{
    protected $table = 'quotation_photos';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'quotation_id',
        'photo_url',
        'photo_description',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($quotationPhoto) {
            $quotationPhoto->id = Uuid::generate()->string;
    	});
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