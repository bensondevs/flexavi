<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Owner extends Model
{
    use HasFactory;
    use Searchable;
    use SoftDeletes;

    protected $table = 'owners';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $with = ['user', 'addresses'];

    protected $searchable = [
        'bank_name',
        'bic_code',
        'bank_account',
        'bank_holder_name',
    ];

    protected $fillable = [
        'user_id',
        'company_id',

        'is_prime_owner',

        'bank_name',
        'bic_code',
        'bank_account',
        'bank_holder_name',
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

    public function addresses()
    {
        return $this->morphMany(Address::class, 'addressable');
    }

    public function scopePrimeOnly($query)
    {
        return $query->where('is_prime_owner', true);
    }

    public function scopeNonPrime($query)
    {
        return $query->where('is_prime_owner', false);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}