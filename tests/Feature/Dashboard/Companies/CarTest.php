<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;
use Laravel\Sanctum\Sanctum;

use App\Models\{ Car, Owner, Company };

class CarTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A load cars test.
     *
     * @return void
     */
    public function test_view_all_cars()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/cars';
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('cars');
        });
    }

    /**
     * A load trashed cars test.
     *
     * @return void
     */
    public function test_view_trashed_cars()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/cars/trasheds';
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('cars');
        });
    }

    /**
     * A load free cars test.
     *
     * @return void
     */
    public function test_view_free_cars()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $url = '/api/dashboard/companies/cars/frees';
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('cars');
        });
    }

    /**
     * A store car test.
     *
     * @return void
     */
    public function test_store_car()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $carData = [
            'brand' => 'A brand',
            'model' => 'A model',
            'year' => 2003,
            'car_name' => 'A car name',
            'car_license' => 'SAMPLE_LICENSE',
        ];
        $url = '/api/dashboard/companies/cars/store';
        $response = $this->post($url, $carData);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * A update car test.
     *
     * @return void
     */
    public function test_set_car_image()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $car = Car::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $carImageData = [
            'id' => $car->id,
            'car_image' => file_get_contents(base_path() . '/tests/Resources/image_base64_example.txt'),
        ];
        $url = '/api/dashboard/companies/cars/set_image';
        $response = $this->post($url, $carImageData);

        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('message');
            $json->where('status', 'success');
            $json->has('car');
        });
    }

    /**
     * A update car test.
     *
     * @return void
     */
    public function test_view_car()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $car = Car::where('company_id', $owner->company_id)->first();

        $url = '/api/dashboard/companies/cars/view?id=' . $car->id;
        $response = $this->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('car');
        });
    }

    /**
     * A view car test.
     *
     * @return void
     */
    public function test_update_car()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $car = Car::where('company_id', $owner->company_id)->first();

        $carData = [
            'id' => $car->id,
            'brand' => 'A brand',
            'model' => 'A model',
            'year' => 2003,
            'car_name' => 'A car name',
            'car_license' => 'SAMPLE_LICENSE',
        ];
        $url = '/api/dashboard/companies/cars/update';
        $response = $this->patch($url, $carData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * A delete car test.
     *
     * @return void
     */
    public function test_delete_car()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        $car = Car::where('company_id', $owner->company_id)->free()->first();

        $carData = [
            'id' => $car->id,
        ];
        $url = '/api/dashboard/companies/cars/delete';
        $response = $this->delete($url, $carData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * A restore car test.
     *
     * @return void
     */
    public function test_restore_car()
    {
        $company = Company::inRandomOrder()->first();
        $owner = $company->owners()->inRandomOrder()->first() ?:
            Owner::factory()->create(['company_id' => $company->id]);
        Sanctum::actingAs(($user = $owner->user), ['*']);

        if (! $car = Car::onlyTrashed()->where('company_id', $owner->company_id)->first()) {
            $car = Car::where('company_id', $owner->company_id)->first();
            $carId = $car->id;
            $car->delete();

            $car = Car::onlyTrashed()->where('id', $carId)->first();
        }

        $carData = [
            'id' => $car->id,
        ];
        $url = '/api/dashboard/companies/cars/restore';
        $response = $this->patch($url, $carData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('car');
            $json->where('status', 'success');
            $json->has('message');
        });
    }
}
