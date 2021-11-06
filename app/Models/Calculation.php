<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Calculation extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'calculations';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'calculationable_type',
        'calculationable_id',

        'calculation',
    ];

    protected $casts = [
        'calculation' => 'json',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($calculation) {
            $calculation->id = Uuid::generate()->string;
    	});
    }

    public function calculationable()
    {
        return $this->morphTo();
    }
}