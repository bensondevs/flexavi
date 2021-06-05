<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class Owner extends Model
{
    protected $table = 'owners';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'user_id',
        'company_id',

        'is_prime_owner',

        'bank_name',
        'bic_code',
        'bank_account',
        'bank_holder_name',
    ];

    protected $hidden = [
        
    ];

    protected $casts = [
        'is_prime_owner' => 'boolean',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($owner) {
            $owner->id = Uuid::generate()->string;

            if (! $owner->company_id) $owner->is_prime_owner = true;
    	});
    }

    public function user()
    {
        return $this->belongsTo(
            'App\Models\User', 
            'user_id', 
            'id'
        );
    }

    public function company()
    {
        return $this->hasOne(
            'App\Models\Company',
            'id',
            'company_id'
        );
    }
}