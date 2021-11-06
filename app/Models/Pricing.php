<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Pricing extends Model
{
    use HasFactory;

    protected $table = 'pricings';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'service_name',
        'price',
        'description',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($pricing) {
            $pricing->id = Uuid::generate()->string;
    	});
    }
}