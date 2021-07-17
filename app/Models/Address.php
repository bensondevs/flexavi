<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Address extends Model
{
    protected $table = 'addresses';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'user_id',

        'address',
        'house_number',
        'house_number_suffix',
        'zipcode',
        'city',
        'province',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($address) {
            $address->id = Uuid::generate()->string;
    	});
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}