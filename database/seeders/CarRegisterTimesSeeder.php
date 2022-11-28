<?php

namespace Database\Seeders;

use App\Enums\Car\CarStatus;
use App\Models\{Car\Car, Car\CarRegisterTime};
use Illuminate\Database\Seeder;

class CarRegisterTimesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $rawTimes = [];
        foreach (Car::all() as $car) {

            $now = now()->copy();
            $shouldReturnAt = $now->addDays(rand(-3, 3));
            $shouldOutAt = $shouldReturnAt->addDays(rand(0, 3));
            $markedOutAt = rand(0, 1) ? $shouldOutAt->addMinutes(rand(0, 60)) : null;
            $markedReturnAt = rand(0, 1) ? $shouldReturnAt->addMinutes(rand(0, 60)) : null;

            $time = [
                'id' => generateUuid(),
                'company_id' => $car->company_id,
                'car_id' => $car->id,
                'should_out_at' => now()->addDays(rand(-3, 0)),
                'should_return_at' => now()->addDays(3),
                'marked_out_at' => rand(0, 1) ? now()->addDays(-2) : null,
                'marked_return_at' => rand(0, 1) ? now()->addDays(2) : null,
            ];

            if ($markedOutAt !== null) {
                $car->status = CarStatus::Out;
            }

            if ($markedReturnAt !== null) {
                $car->status = CarStatus::Free;
            }

            if ($car->isDirty()) {
                $car->save();
            }

            $rawTimes[] = $time;
        }

        CarRegisterTime::insert($rawTimes);
    }
}
