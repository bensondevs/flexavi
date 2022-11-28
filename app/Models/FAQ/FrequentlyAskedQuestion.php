<?php

namespace App\Models\FAQ;

use App\Observers\FrequentlyAskedQuestionObserver as Observer;
use Illuminate\Database\Eloquent\{Model, SoftDeletes};
use Illuminate\Database\Eloquent\Factories\HasFactory;


class FrequentlyAskedQuestion extends Model
{

    use HasFactory;
    use SoftDeletes;
    use \App\Traits\Searchable;

    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'frequently_asked_questions';

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
    public $searchableFields = ['title', 'content'];

    /**
     * Set which columns are mass fillable
     *
     * @var array
     */
    protected $fillable = ['title', 'content'];

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
}
