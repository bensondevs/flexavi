<?php

namespace Tests\Feature\Dashboard\Company\Search;

use App\Services\Algolia\AlgoliaService;
use App\Traits\FeatureTestUsables;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class GeneralSearchTest extends TestCase
{
    use WithFaker;
    use FeatureTestUsables;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function test_search()
    {
        // get searchable models
        $searchableModels = AlgoliaService::searchableModels();

        // disable search sync on each searchable models
        foreach ($searchableModels as $model) {
            $model::disableSearchSyncing();
        };

        $this->authenticateAsOwner();

        $keyword = "flexavi";

        $response = $this->getJson("/api/dashboard/companies/general_search?keyword=$keyword");
        $response->assertOk();

        foreach ($response->json('data.employees') as $employee) {
            $this->assertStringContainsStringIgnoringCase($keyword, $employee['user']['fullname']);
        }

        // enable search sync on each searchable models
        foreach ($searchableModels as $model) {
            $model::enableSearchSyncing();
        };
    }
}
