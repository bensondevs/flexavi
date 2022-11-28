<?php

namespace Tests\Feature\Dashboard\Company\Inspection;

use App\Models\Appointment\Appointment;
use App\Models\Customer\Customer;
use App\Models\Employee\Employee;
use App\Models\Inspection\Inspection;
use App\Models\Inspection\Inspector;
use App\Models\User\User;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class InspectionTest extends TestCase
{
    use WithFaker;

    /**
     * Test populate company inspections
     *
     * @return void
     */
    public function test_populate_company_inspections()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $response = $this->getJson(
            '/api/dashboard/companies/inspections'
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('inspections');
            $json->whereType('inspections.data', 'array');

            // pagination meta
            $json->has('inspections.current_page');
            $json->has('inspections.first_page_url');
            $json->has('inspections.from');
            $json->has('inspections.last_page');
            $json->has('inspections.last_page_url');
            $json->has('inspections.links');
            $json->has('inspections.next_page_url');
            $json->has('inspections.path');
            $json->has('inspections.per_page');
            $json->has('inspections.prev_page_url');
            $json->has('inspections.to');
            $json->has('inspections.total');
        });
    }

    /**
     * Test populate customer inspections
     *
     * @return void
     */
    public function test_populate_customer_inspections()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $customer = Customer::factory()->for($user->owner->company)->create();
        $response = $this->getJson(
            '/api/dashboard/companies/inspections/of_customer?customer_id=' . $customer->id
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('inspections');
            $json->whereType('inspections.data', 'array');

            // pagination meta
            $json->has('inspections.current_page');
            $json->has('inspections.first_page_url');
            $json->has('inspections.from');
            $json->has('inspections.last_page');
            $json->has('inspections.last_page_url');
            $json->has('inspections.links');
            $json->has('inspections.next_page_url');
            $json->has('inspections.path');
            $json->has('inspections.per_page');
            $json->has('inspections.prev_page_url');
            $json->has('inspections.to');
            $json->has('inspections.total');
        });
    }

    /**
     * Test populate employee inspections
     *
     * @return void
     */
    public function test_populate_employee_inspections()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $owner = $user->owner;
        $company = $owner->company;
        $customer = Customer::factory()->for($company)->create();

        $employee = Employee::factory()->for($company)->create();

        // the created Model's ids
        $appointmentIds = [];
        $inspectionIds = [];
        // there's a reason why this factory doesn't use Model::factory->count(number)
        // reason : if not using for loop the factory not running properly
        // :::: contact me for more information "arfan2173@gmail.com" ::::
        for ($i = 1; $i <= 3; $i++) {
            Appointment::factory()
                ->for($company)->for($customer)
                ->inspection()
                ->createQuietly()->each(function ($appointment) use (&$appointmentIds, &$inspectionIds, $employee) {
                    array_push($appointmentIds, $appointment->id);

                    // create the inspection
                    Inspection::factory()
                        ->for($appointment)
                        ->create()->each(function ($inspection) use (&$inspectionIds, $employee) {
                            array_push($inspectionIds, $inspection->id);

                            // create the inspection pivot table
                            Inspector::create([
                                'inspection_id' => $inspection->id,
                                'employee_id' => $employee->id
                            ]);
                        });
                });
        }

        $response = $this->get("/api/dashboard/companies/inspections/of_employee?employee_id=" . $employee->id);

        foreach ($appointmentIds as $id) {
            $this->assertDatabaseHas((new Appointment())->getTable(), ['id' => $id]);
        }
        foreach ($inspectionIds as $id) {
            $this->assertDatabaseHas((new Inspection())->getTable(), ['id' => $id]);
        }

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('inspections');
            $json->whereType('inspections.data', 'array');

            // pagination meta
            $json->has('inspections.current_page');
            $json->has('inspections.first_page_url');
            $json->has('inspections.from');
            $json->has('inspections.last_page');
            $json->has('inspections.last_page_url');
            $json->has('inspections.links');
            $json->has('inspections.next_page_url');
            $json->has('inspections.path');
            $json->has('inspections.per_page');
            $json->has('inspections.prev_page_url');
            $json->has('inspections.to');
            $json->has('inspections.total');
        });
    }

    /**
     * Test populate trashed inspections
     *
     * @return void
     */
    public function test_populate_trashed_inspections()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $customer = Customer::factory()->for($user->owner->company)->create();
        $response = $this->getJson(
            '/api/dashboard/companies/inspections/trasheds'
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('inspections');
            $json->whereType('inspections.data', 'array');

            // pagination meta
            $json->has('inspections.current_page');
            $json->has('inspections.first_page_url');
            $json->has('inspections.from');
            $json->has('inspections.last_page');
            $json->has('inspections.last_page_url');
            $json->has('inspections.links');
            $json->has('inspections.next_page_url');
            $json->has('inspections.path');
            $json->has('inspections.per_page');
            $json->has('inspections.prev_page_url');
            $json->has('inspections.to');
            $json->has('inspections.total');
        });
    }

    /**
     * Test view company inspection
     *
     * @return void
     */
    public function test_view_company_inspection()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();
        $appointment = Appointment::factory()
            ->for($company)
            ->for($customer)
            ->inspection()->createQuietly();
        $inspection = Inspection::factory()
            ->for($appointment)
            ->for($company)->create();

        $response = $this->getJson(
            '/api/dashboard/companies/inspections/view?id=' . $inspection->id
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('inspection');
            $json->has('inspection.id');
        });
    }

    /**
     * Test delete company inspection
     *
     * @return void
     */
    public function test_delete_company_inspection()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();
        $appointment = Appointment::factory()
            ->for($company)
            ->for($customer)
            ->inspection()->createQuietly();
        $inspection = Inspection::factory()
            ->for($appointment)
            ->for($company)->create();

        $response = $this->deleteJson(
            '/api/dashboard/companies/inspections/delete',
            [
                'id' => $inspection->id
            ]
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });
    }

    /**
     * Test hard delete company inspection
     *
     * @return void
     */
    public function test_delete_company_inspection_permanently()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();
        $appointment = Appointment::factory()
            ->for($company)
            ->for($customer)
            ->inspection()->createQuietly();
        $inspection = Inspection::factory()
            ->for($appointment)
            ->for($company)->create();

        $response = $this->deleteJson(
            '/api/dashboard/companies/inspections/delete',
            [
                'id' => $inspection->id,
                'force' => true
            ]
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });
    }


    /**
     * Test hard restore company inspection
     *
     * @return void
     */
    public function test_restore_company_inspection()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;
        $customer = Customer::factory()->for($company)->create();
        $appointment = Appointment::factory()
            ->for($company)
            ->for($customer)
            ->inspection()->createQuietly();
        $inspection = Inspection::factory()
            ->for($appointment)
            ->for($company)->create();

        $inspection->delete();

        $response = $this->patchJson(
            '/api/dashboard/companies/inspections/restore',
            [
                'id' => $inspection->id,
            ]
        );
        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('inspection');
            $json->has('inspection.id');
            // status meta
            $json->where('status', 'success');
            $json->has('message');
        });
    }
}
