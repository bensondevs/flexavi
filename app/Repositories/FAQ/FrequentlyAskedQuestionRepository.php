<?php

namespace App\Repositories\FAQ;

use App\Models\FAQ\FrequentlyAskedQuestion;
use App\Repositories\Base\BaseRepository;

class FrequentlyAskedQuestionRepository extends BaseRepository
{
    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct()
    {
        $this->setInitModel(new FrequentlyAskedQuestion());
    }
}
