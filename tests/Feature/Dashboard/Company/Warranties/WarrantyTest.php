<?php

namespace Tests\Feature\Dashboard\Company\Warranties;

use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class WarrantyTest extends TestCase
{
    use WithFaker;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    /* public function test_populate_employee_warranties()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();

        $employee = Employee::factory()->for($company)->create();

        // there's a reason why this factory doesn't use Model::factory->count(number)
        // reason : if not using for loop the factory not running properly
        // :::: contact me for more information "arfan2173@gmail.com" ::::
        for ($i = 1; $i <= 3; $i++) {
            Appointment::factory()
                ->for($company)->for($customer)
                ->createOneQuietly()->each(function ($appointment) use ($company, $customer, $employee) {
                    Warranty::factory()
                        ->created()
                        ->createOneQuietly([
                            'company_id' => $company->id,
                            'appointment_id' => $appointment->id,
                        ]);

                    AppointmentEmployee::create([
                        'appointment_id' => $appointment->id,
                        'user_id' => $employee->user_id,
                    ]);
                });
        }

        $response = $this->get("/api/dashboard/companies/warranties/of_employee?employee_id=$employee->id");

        $response->assertStatus(200);
    } */
}
