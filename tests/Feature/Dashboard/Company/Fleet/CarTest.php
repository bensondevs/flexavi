<?php

namespace Tests\Feature\Dashboard\Company\Fleet;

use App\Models\{Car\Car, User\User};
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class CarTest extends TestCase
{
    /**
     * Test populate company cars
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_populate_company_cars()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $response = $this->getJson('/api/dashboard/companies/cars');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('cars');
            $json->whereType('cars.data', 'array');

            // pagination meta
            $json->has('cars.current_page');
            $json->has('cars.first_page_url');
            $json->has('cars.from');
            $json->has('cars.last_page');
            $json->has('cars.last_page_url');
            $json->has('cars.links');
            $json->has('cars.next_page_url');
            $json->has('cars.path');
            $json->has('cars.per_page');
            $json->has('cars.prev_page_url');
            $json->has('cars.to');
            $json->has('cars.total');
        });
    }
    */

    /**
     * Test populate free company cars
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_populate_free_company_cars()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $response = $this->getJson('/api/dashboard/companies/cars/frees');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('cars');
            $json->whereType('cars.data', 'array');

            // pagination meta
            $json->has('cars.current_page');
            $json->has('cars.first_page_url');
            $json->has('cars.from');
            $json->has('cars.last_page');
            $json->has('cars.last_page_url');
            $json->has('cars.links');
            $json->has('cars.next_page_url');
            $json->has('cars.path');
            $json->has('cars.per_page');
            $json->has('cars.prev_page_url');
            $json->has('cars.to');
            $json->has('cars.total');
        });
    }
    */

    /**
     * Test populate company trashed cars
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_populate_company_trashed_cars()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $response = $this->getJson('/api/dashboard/companies/cars/trasheds');
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('cars');
            $json->whereType('cars.data', 'array');

            // pagination meta
            $json->has('cars.current_page');
            $json->has('cars.first_page_url');
            $json->has('cars.from');
            $json->has('cars.last_page');
            $json->has('cars.last_page_url');
            $json->has('cars.links');
            $json->has('cars.next_page_url');
            $json->has('cars.path');
            $json->has('cars.per_page');
            $json->has('cars.prev_page_url');
            $json->has('cars.to');
            $json->has('cars.total');
        });
    }
    */

    /**
     * Test get a company car
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_get_company_car()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $car = Car::factory()->create();
        $response = $this->getJson(
            '/api/dashboard/companies/cars/view?id=' . $car->id
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('car');
            $json->has('car.id');
        });
    }
    */

    /**
     * Test store a company car
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_store_company_car()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $response = $this->postJson('/api/dashboard/companies/cars/store', [
            'brand' => 'Toyota',
            'model' => 'Carry',
            'year' => 1998,
            'car_name' => 'Suzuki BX 2012 Bulldozer',
            'car_license' => 'BM 14386',
            'insured' => true,
            'insurance_tax' => 10,
            'apk' => now()->addYears(rand(1, 2))->toDateTimeString(),
            'car_image' => UploadedFile::fake()
                ->image('image.png', 100, 100)
                ->size(100),
        ]);
        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('car');
            $json->has('car.id');

            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });
    }
    */

    /**
     * Test update a company car
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_update_company_car()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $car = Car::factory()->create();
        $response = $this->postJson('/api/dashboard/companies/cars/update', [
            'id' => $car->id,
            'brand' => 'Toyota',
            'model' => 'Carry',
            'year' => '1998',
            'car_name' => 'Suzuki BX 2012 Bulldozer',
            'apk' => now()->addYears(rand(1, 2))->toDateTimeString(),
            'car_license' => 'BM 14386',
            'insured' => true,
        ]);
        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('car');
            $json->has('car.id');

            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });
    }
    */

    /**
     * Test update a company car image
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_update_company_car_image()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $car = Car::factory()->create();
        $response = $this->postJson('/api/dashboard/companies/cars/set_image', [
            'id' => $car->id,
            'car_image' => UploadedFile::fake()
                ->image('image.png', 100, 100)
                ->size(100),
        ]);
        $response->assertStatus(201);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('car');
            $json->has('car.id');

            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });
    }
    */

    /**
     * Test delete a company car
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_delete_company_car()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $car = Car::factory()->create();
        $response = $this->deleteJson('/api/dashboard/companies/cars/delete', [
            'id' => $car->id,
        ]);
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });
    }
    */

    /**
     * Test delete a company car permanently
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_delete_company_car_permanently()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $car = Car::factory()->create();
        $response = $this->deleteJson('/api/dashboard/companies/cars/delete', [
            'id' => $car->id,
            'force' => true,
        ]);
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });
    }
    */

    /**
     * Test restore a company trashed car
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_restore_company_trashed_car()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $car = Car::factory()->create();
        $car->delete();
        $response = $this->putJson('/api/dashboard/companies/cars/restore', [
            'id' => $car->id,
        ]);
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('car');
            $json->has('car.id');

            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });
    }
    */
}
