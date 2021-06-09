<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Inspection extends Model
{
    use SoftDeletes;

    protected $table = 'inspections';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'company_id',

        'signable_type',
        'signable_id',

        'is_signed',
        
        'sidenote',
    ];

    protected $hidden = [
        
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($inspection) {
            $inspection->id = Uuid::generate()->string;
    	});
    }

    public function quotationOrWorkContract()
    {
        return $this->morphTo();
    }

    public function inspectors()
    {
        return $this->hasMany(
            'App\Models\Inspector', 
            'inspection_id', 
            'id'
        );
    }

    public function company()
    {
        return $this->belongsTo(
            'App\Models\Company', 
            'company_id', 
            'id'
        );
    }
}