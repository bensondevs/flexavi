<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

use App\Models\Car;
use App\Models\Owner;

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/cars';
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/cars/trasheds';
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/cars/frees';
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $carData = [
            'brand' => 'A brand',
            'model' => 'A model',
            'year' => 2003,
            'car_name' => 'A car name',
            'car_license' => 'SAMPLE_LICENSE',
        ];
        $url = '/api/dashboard/companies/cars/store';
        $response = $this->withHeaders($headers)->post($url, $carData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

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
        $response = $this->withHeaders($headers)->post($url, $carImageData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $car = Car::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/cars/view?id=' . $car->id;
        $response = $this->withHeaders($headers)->get($url);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $car = Car::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $carData = [
            'id' => $car->id,
            'brand' => 'A brand',
            'model' => 'A model',
            'year' => 2003,
            'car_name' => 'A car name',
            'car_license' => 'SAMPLE_LICENSE',
        ];
        $url = '/api/dashboard/companies/cars/update';
        $response = $this->withHeaders($headers)->patch($url, $carData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $car = Car::where('company_id', $owner->company_id)->free()->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $carData = [
            'id' => $car->id,
        ];
        $url = '/api/dashboard/companies/cars/delete';
        $response = $this->withHeaders($headers)->delete($url, $carData);

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
        $owner = Owner::whereHas('user')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        if (! $car = Car::onlyTrashed()->where('company_id', $owner->company_id)->first()) {
            $car = Car::where('company_id', $owner->company_id)->first();
            $carId = $car->id;
            $car->delete();

            $car = Car::onlyTrashed()->where('id', $carId)->first();
        }

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $carData = [
            'id' => $car->id,
        ];
        $url = '/api/dashboard/companies/cars/restore';
        $response = $this->withHeaders($headers)->patch($url, $carData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('car');
            $json->where('status', 'success');
            $json->has('message');
        });
    }
}
