<?php

namespace Tests\Unit\Factories\Fleet;

use App\Models\Car\Car;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\Faker;
use Tests\TestCase;

class CarTest extends TestCase
{
    use WithFaker;

    /**
     * Test create a company car instance
     *
     * @return void
     */
    public function test_create_company_car_instance()
    {
        // make an instance
        $car = Car::factory()->create();

        // assert the instance
        $this->assertNotNull($car);
        $this->assertModelExists($car);
        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'brand' => $car->brand,
            'model' => $car->model,
            'year' => $car->year,
            'car_name' => $car->car_name,
            'car_license' => $car->car_license,
        ]);

        // assert the model relations
        $this->assertNotNull($car->company);
        $this->assertModelExists($car->company);
    }

    /**
     * Test create multiple company car instances
     *
     * @return void
     */
    public function test_create_multiple_company_car_instances()
    {
        // make the instances
        $count = 10;
        $cars = Car::factory($count)->create();

        // assert the instances
        $this->assertTrue(count($cars) === $count);
    }

    /**
     * Test update a company car instance
     *
     * @return void
     */
    public function test_update_company_car_instance()
    {
        // make an instance
        $car = Car::factory()->create();

        // assert the instance
        $this->assertNotNull($car);
        $this->assertModelExists($car);
        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'brand' => $car->brand,
            'model' => $car->model,
            'year' => $car->year,
            'car_name' => $car->car_name,
            'car_license' => $car->car_license,
        ]);

        // generate dummy data
        $brand = Str::title($this->faker->word);
        $model = Str::title($this->faker->word);
        $year = date('Y');

        // update instance
        $car->update([
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
        ]);

        // assert the updated instance
        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'brand' => $brand,
            'model' => $model,
            'year' => $year,
            'car_name' => $car->car_name,
            'car_license' => $car->car_license,
        ]);
    }

    /**
     * Test soft delete a company car instance
     *
     * @return void
     */
    public function test_soft_delete_company_car_instance()
    {
        // make an instance
        $car = Car::factory()->create();

        // assert the instance
        $this->assertNotNull($car);
        $this->assertModelExists($car);
        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'brand' => $car->brand,
            'model' => $car->model,
            'year' => $car->year,
            'car_name' => $car->car_name,
            'car_license' => $car->car_license,
        ]);

        // soft delete the instance
        $car->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($car);
    }

    /**
     * Test hard delete a company car instance
     *
     * @return void
     */
    public function test_hard_delete_company_car_instance()
    {
        // make an instance
        $car = Car::factory()->create();

        // assert the instance
        $this->assertNotNull($car);
        $this->assertModelExists($car);
        $this->assertModelExists($car);
        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'brand' => $car->brand,
            'model' => $car->model,
            'year' => $car->year,
            'car_name' => $car->car_name,
            'car_license' => $car->car_license,
        ]);

        // hard delete the instance
        $carId = $car->id;
        $car->forceDelete();

        // assert the hard deleted instance
        $this->assertModelMissing($car);
        $this->assertDatabaseMissing('cars', [
            'id' => $carId,
        ]);
    }

    /**
     * Test restore a trashed company car instance
     *
     * @return void
     */
    public function test_restore_trashed_company_car_instance()
    {
        // make an instance
        $car = Car::factory()->create();

        // assert the instance
        $this->assertNotNull($car);
        $this->assertModelExists($car);
        $this->assertDatabaseHas('cars', [
            'id' => $car->id,
            'brand' => $car->brand,
            'model' => $car->model,
            'year' => $car->year,
            'car_name' => $car->car_name,
            'car_license' => $car->car_license,
        ]);

        // soft delete the instance
        $car->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($car);

        // restore the trashed instance
        $car->restore();

        // assert the restored instance
        $this->assertNotSoftDeleted($car);
    }
}
