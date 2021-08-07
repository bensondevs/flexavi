<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Warranty extends Model
{
    protected $table = 'warranties';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'work_id',
        'warranty_due',
        'internal_note',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($warranty) {
            $warranty->id = Uuid::generate()->string;
    	});
    }

    public function workContract()
    {
        return $this->belongsTo(WorkContract::class);
    }
}