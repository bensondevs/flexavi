<?php

namespace App\Models\HelpDesk;

use App\Models\Company\Company;
use App\Models\User\User;
use App\Observers\HelpDeskObserver as Observer;
use App\Traits\Searchable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HelpDesk extends Model
{
    use HasFactory;
    use Searchable;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'help_desks';

    /**
     * Table name primary key
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * Set timestamp each time model is saved
     *
     * @var bool
     */
    public $timestamps = true;

    /**
     * Set whether primary key use incrementing value or not
     *
     * @var bool
     */
    public $incrementing = false;

    /**
    * Set which columns are searchable
    *
    * @var array
    */
    public array $searchableFields = ['company_id', 'user_id','title', 'content'];

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = ['company_id', 'user_id', 'title', 'content'];

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
        self::observe(Observer::class);
    }

    /**
     * Get the company
     *
     * @return BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Get the user
     *
     * @return BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
