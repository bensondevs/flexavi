<?php

namespace App\Models;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Webpatser\Uuid\Uuid;

class EmailVerification extends Model
{
    protected $table = 'email_verifications';
    protected $primaryKey = 'id';
    public $timestamps = true;
    public $incrementing = false;

    protected $fillable = [
        'model',
        'model_id',
        'model_verification_column',
    ];

    protected $casts = [
        'expired_at' => 'datetime',
    ];

    protected static function boot()
    {
    	parent::boot();

    	self::creating(function ($verification) {
            $verification->code = random_string(10);
    	});
    }

    public function getEncryptedCodeAttribute()
    {
        return encryptString($this->attributes['code']);
    }

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

    public static function findByCode(string $code)
    {
        return self::where('code', $code)->first();
    }

    public static function findByCodeOrFail(string $code)
    {
        return self::where('code', $code)->firstOrFail();
    }
}