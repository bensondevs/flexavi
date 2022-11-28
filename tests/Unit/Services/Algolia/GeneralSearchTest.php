<?php

namespace Tests\Unit\Services\Algolia;

use App\Models\{Appointment\Appointment,
    Car\Car,
    Customer\Customer,
    Customer\CustomerNote,
    HelpDesk\HelpDesk,
    Employee\Employee,
    FAQ\FrequentlyAskedQuestion,
    Invoice\Invoice,
    Owner\Owner,
    Quotation\Quotation,
    Workday\Workday,
    Worklist\Worklist,
    WorkService\WorkService,
    WorkContract\WorkContract,
    Notification\Notification,
};
use App\Services\Algolia\AlgoliaService;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Tests\TestCase;

class GeneralSearchTest extends TestCase
{
    use WithFaker;

    /**
     *  The Searchable models
     *
     * @var array
     */
    private array $searchableModels = [
        Employee::class,
        Owner::class,
        Appointment::class,
        Quotation::class,
        Invoice::class,
        WorkService::class,
        Workday::class,
        Worklist::class,
        WorkContract::class,
        FrequentlyAskedQuestion::class,
        Customer::class,
        CustomerNote::class,
        Car::class,
        HelpDesk::class,
        Notification::class,
    ];

    /**
     *  Test AlgoliaSearch::searchableModels (method)
     *
     * @return void
     */
    public function test_algolia_search_service_get_searchable_models(): void
    {
        foreach (AlgoliaService::searchableModels() as $searchableModel) {
            $isSearchable = in_array(
                $searchableModel,
                $this->searchableModels
            );
            $this->assertTrue($isSearchable);
        }
    }

    /**
     *  Test model has searchable trait
     *
     * @return void
     */
    public function test_model_has_searchable_trait(): void
    {
        foreach ($this->searchableModels as $searchableModel) {
            $this->assertTrue(
                in_array(
                    \App\Traits\Searchable::class,
                    class_uses_recursive($searchableModel)
                )
            );
        }
    }

    /**
     * Test some model must be excluded
     *
     * @return void
     */
    public function test_some_model_must_be_excluded(): void
    {
        $allModels = (function () {
            $path = app_path("Models");
            $files = File::allFiles($path);

            $modelNames = array_map(function ($file) {
                $pathinfo = pathinfo($file) ;
                $dirname = $pathinfo["dirname"] ;
                $namespace = "App\\" . (Str::afterLast( // get the class namespace
                    Str::replace("/", "\\", $dirname),
                    'app\\'
                ));
                $className = Str::beforeLast($pathinfo["basename"], ".") ; // get filename without extension
                return "$namespace\\$className"; // returned model namespace + class name
            }, $files);

            return $modelNames ;
        })();

        $mustIncludedModels = $this->searchableModels;
        $mustExcludedModels = array_filter(
            $allModels,
            fn ($model) => !in_array($model, $mustIncludedModels)
        );

        foreach ($mustExcludedModels as $excludedModel) {
            // make sure the excluded searchable model hasn't had "Searchable" trait
            $this->assertFalse(
                in_array(
                    \App\Traits\Searchable::class,
                    class_uses_recursive($excludedModel)
                )
            );
        }
    }
}
