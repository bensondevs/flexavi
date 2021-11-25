<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class EmailVerification extends Model
{
    use HasFactory;

    /**
     * The table name
     * 
     * @var string
     */
    protected $table = 'email_verifications';

    /**
     * The primary key of the model
     * 
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Timestamp recording
     * 
     * @var bool
     */
    public $timestamps = true;

    /**
     * Set whether primary key use increment or not
     * 
     * @var bool
     */
    public $incrementing = false;

    /**
     * Set which columns are mass fillable
     * 
     * @var array
     */
    protected $fillable = [
        'model',
        'model_id',
        'model_verification_column',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'expired_at' => 'datetime',
    ];

    /**
     * Perform any actions required before the model boots.
     * This is where observer should be put.
     * Any events and listener logic can be added in this method
     *
     * @return void
     */
    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($verification) {
            $verification->code = random_string(10);
    	});
    }

    /**
     * Get encrypted string for verification
     * This will improve the security of the verification code
     * Because the verification code will appear as parameter in url
     * So it's better to make it as encrypted to make it hard to conterfeit
     * 
     * @return string
     */
    public function getEncryptedCodeAttribute()
    {
        return encryptString($this->attributes['code']);
    }

    /**
     * Do verification to the email assigned to any model
     * 
     * @return bool
     */
    public function verify()
    {
        $model = $this->attributes['model'];
        $id = $this->attributes['model_id'];
        $verificationColumn = $this->attributes['model_verification_column'];

        if ($this->attributes['expired_at'] <= carbon()->now()) {
            abort(422, 'This verification is already expired. Please request new verification email.');
        }

        $model = $model->findOrFail($id);

        $model->{$verificationColumn} = carbon()->now();
        return $model->save();
    }

    /**
     * Find email verification by code
     * 
     * @static
     * @param string  $code
     * @return \App\Models\EmailVerification
     */
    public static function findByCode(string $code)
    {
        return self::where('code', $code)->first();
    }

    /**
     * Find email verification by encrypted code
     * 
     * @static
     * @param string  $encryptedCode
     * @return \App\Models\EmailVerification
     */
    public static function findByEncryptedCode(string $encryptedCode)
    {
        $code = decryptString($encryptedCode);
        return self::findByCode($code);
    }

    /**
     * Find email verification by code and abort 404 if not found
     * 
     * @static
     * @param string  $code
     * @return \App\Models\EmailVerification
     */
    public static function findByCodeOrFail(string $code)
    {
        return self::where('code', $code)->firstOrFail();
    }
}