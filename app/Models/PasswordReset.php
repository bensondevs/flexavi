<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use App\Traits\Searchable;

class PasswordReset extends Model
{
    use Searchable;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'password_resets';

    /**
     * Table name primary key
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Timestamp recording
     * 
     * @var bool
     */
    public $timestamps = false;

    /**
     * Set whether primary key use increment or not
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Set which columns are searchable
     * 
     * @var array
     */
    protected $searchable = [
        //
    ];

    /**
     * Set which columns are mass fillable
     * 
     * @var bool
     */
    protected $fillable = [
        'email',
        'token',
        'created_at',
    ];

    /**
     * Function that will be run whenever event happened
     * 
     * @return  void
     */
    protected static function boot()
    {
    	parent::boot();

        self::creating(function ($passwordReset) {
            $passwordReset->created_at = now();
        });
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'email', 'email');
    }
}