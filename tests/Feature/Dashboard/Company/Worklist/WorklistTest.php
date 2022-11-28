<?php

namespace Tests\Feature\Dashboard\Company\Worklist;

use App\Enums\Worklist\WorklistSortingRouteStatus;
use App\Models\{Appointment\Appointment,
    Appointment\Appointmentable,
    Car\Car,
    Customer\Customer,
    Employee\Employee,
    Owner\Owner,
    User\User,
    Workday\Workday,
    Worklist\Worklist,
    Worklist\WorklistEmployee};
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class WorklistTest extends TestCase
{
    /**
     * Test populate company worklists
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
      public function test_populate_company_worklists()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $response = $this->getJson('/api/dashboard/companies/worklists');

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('worklists');
            $json->whereType('worklists.data', 'array');

            // pagination meta
            $json->has('worklists.current_page');
            $json->has('worklists.first_page_url');
            $json->has('worklists.from');
            $json->has('worklists.last_page');
            $json->has('worklists.last_page_url');
            $json->has('worklists.links');
            $json->has('worklists.next_page_url');
            $json->has('worklists.path');
            $json->has('worklists.per_page');
            $json->has('worklists.prev_page_url');
            $json->has('worklists.to');
            $json->has('worklists.total');
        });
    }
     */


    /**
     * Test populate company workday worklists by id
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_populate_company_workday_worklists_by_id()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $workday = Workday::factory()
            ->for($user->owner->company)
            ->create();

        $response = $this->getJson('/api/dashboard/companies/worklists/of_workday?workday_id=' . $workday->id);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('worklists');
            $json->whereType('worklists.data', 'array');

            // pagination meta
            $json->has('worklists.current_page');
            $json->has('worklists.first_page_url');
            $json->has('worklists.from');
            $json->has('worklists.last_page');
            $json->has('worklists.last_page_url');
            $json->has('worklists.links');
            $json->has('worklists.next_page_url');
            $json->has('worklists.path');
            $json->has('worklists.per_page');
            $json->has('worklists.prev_page_url');
            $json->has('worklists.to');
            $json->has('worklists.total');
        });
    }
     */


    /**
     * Test populate company workday worklists by date
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
   public function test_populate_company_workday_worklists_by_date()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $workday = Workday::factory()
            ->for($user->owner->company)
            ->create();

        $response = $this->getJson('/api/dashboard/companies/worklists/of_workday?date=' . $workday->date);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('worklists');
            $json->whereType('worklists.data', 'array');

            // pagination meta
            $json->has('worklists.current_page');
            $json->has('worklists.first_page_url');
            $json->has('worklists.from');
            $json->has('worklists.last_page');
            $json->has('worklists.last_page_url');
            $json->has('worklists.links');
            $json->has('worklists.next_page_url');
            $json->has('worklists.path');
            $json->has('worklists.per_page');
            $json->has('worklists.prev_page_url');
            $json->has('worklists.to');
            $json->has('worklists.total');
        });
    }
     */


    /**
     * Test populate employee worklists by id
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_populate_employee_worklists_by_id()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );
        $company = $user->owner->company;

        $employee = Employee::factory()->for($company)->create();

        $worklists = Worklist::factory()->count(rand(2, 5))
            ->for($company)->for($employee->user)
            ->create()
            ->each(
                fn ($wl) => WorklistEmployee::create([
                    'user_id'  => $wl->user_id,
                    'worklist_id' => $wl->id,
                ])
            );

        $response = $this->getJson("/api/dashboard/companies/worklists/of_employee?employee_id=" . $employee->id);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('worklists');
            $json->whereType('worklists.data', 'array');

            // pagination meta
            $json->has('worklists.current_page');
            $json->has('worklists.first_page_url');
            $json->has('worklists.from');
            $json->has('worklists.last_page');
            $json->has('worklists.last_page_url');
            $json->has('worklists.links');
            $json->has('worklists.next_page_url');
            $json->has('worklists.path');
            $json->has('worklists.per_page');
            $json->has('worklists.prev_page_url');
            $json->has('worklists.to');
            $json->has('worklists.total');
        });
    }
     */


    /**
     * Test populate company trashed worklists
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
    public function test_populate_company_trashed_worklists()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $response = $this->getJson('/api/dashboard/companies/worklists/trasheds');

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('worklists');
            $json->whereType('worklists.data', 'array');

            // pagination meta
            $json->has('worklists.current_page');
            $json->has('worklists.first_page_url');
            $json->has('worklists.from');
            $json->has('worklists.last_page');
            $json->has('worklists.last_page_url');
            $json->has('worklists.links');
            $json->has('worklists.next_page_url');
            $json->has('worklists.path');
            $json->has('worklists.per_page');
            $json->has('worklists.prev_page_url');
            $json->has('worklists.to');
            $json->has('worklists.total');
        });
    }
     */



    /**
     * Test view worklist
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
      public function test_view_worklist()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;
        $customer = Customer::factory()
            ->for($company)->create();
        $worklist = Worklist::factory()
            ->for($company)->create();
        $appointments = Appointment::factory()
            ->for($company)
            ->for($customer)
            ->count(rand(3, 5))->createQuietly();
        $employees = Employee::factory()
            ->for($company)
            ->count(rand(3, 5))->create();
        WorklistEmployee::insert(
            array_map(fn ($employee) => [
                'id' => generateUuid(),
                "worklist_id" => $worklist->id,
                "user_id" => $employee["user"]["id"]
            ], $employees->toArray())
        );
        Appointmentable::insert(
            array_map(fn ($appointment) => [
                'id' => generateUuid(),
                'order_index' => rand(1, 10),
                'company_id' => $company->id,
                'appointmentable_id' => $worklist->id,
                'appointmentable_type' => get_class($worklist),
                'appointment_id' => $appointment["id"],
            ], $appointments->toArray())
        );

        $response = $this->getJson(
            "/api/dashboard/companies/worklists/view?id={$worklist->id}" .
                "&with_appointments_count=true"  .
                "&with_employees.user=true"
        );

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('worklist');
            $json->has('worklist.id');
        });
    }
     */


    /**
     * Test view trashed worklist
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
 public function test_view_trashed_worklist()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $worklist = Worklist::factory()->for($user->owner->company)->create();

        $worklist->delete();

        $response = $this->getJson('/api/dashboard/companies/worklists/trasheds/view?id=' . $worklist->id);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('worklist');

            $json->has('worklist.id');
        });
    }
     */


    /**
     * Test store worklist
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
public function test_store_worklist()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $workday = Workday::factory()
            ->for($company)
            ->create();

        $car = Car::factory()
            ->for($company)
            ->create();

        $pic = Owner::factory()
            ->for($company)
            ->create();

        $employees = Employee::factory()
            ->for($company)
            ->count(rand(1, 3))
            ->create();

        $response = $this->postJson('/api/dashboard/companies/worklists/store', [
            'workday_id' => $workday->id,
            'worklist_name' => 'Worklist 1',
            'car_id' => $car->id,
            'user_id' => $pic->user->id,
            'employee_ids' => $employees->pluck('user_id')->toArray()
        ]);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('worklist');
            $json->has('worklist.id');
            $json->where('status', 'success');
            $json->where('message', 'Successfully save worklist.');
        });
    }
     */


    /**
     * Test update worklist
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
  public function test_update_worklist()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $worklist = Worklist::factory()
            ->for($company)
            ->create();

        $workday = Workday::factory()
            ->for($company)
            ->create();

        $car = Car::factory()
            ->for($company)
            ->create();

        $pic = Owner::factory()
            ->for($company)
            ->create();

        $employees = Employee::factory()
            ->for($company)
            ->count(rand(1, 3))
            ->create();

        $response = $this->putJson('/api/dashboard/companies/worklists/update', [
            'worklist_id' => $worklist->id,
            'workday_id' => $workday->id,
            'worklist_name' => 'Worklist 1',
            'car_id' => $car->id,
            'user_id' => $pic->user->id,
            'employee_ids' => $employees->pluck('user_id')->toArray()
        ]);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('worklist');
            $json->has('worklist.id');
            $json->where('status', 'success');
            $json->where('message', 'Successfully save worklist.');
        });
    }
     */


    /**
     * Test delete worklist
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
      public function test_delete_worklist()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $worklist = Worklist::factory()
            ->for($company)
            ->create();

        $response = $this->deleteJson('/api/dashboard/companies/worklists/delete', [
            'worklist_id' => $worklist->id,
        ]);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->where('message', 'Successfully delete worklist.');
        });
    }
     */


    /**
     * Test delete worklist
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
         public function test_restore_worklist()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $worklist = Worklist::factory()
            ->for($company)
            ->create();

        $worklist->delete();

        $response = $this->putJson('/api/dashboard/companies/worklists/restore', [
            'worklist_id' => $worklist->id,
        ]);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->where('message', 'Successfully restore worklist.');

            $json->has('worklist');
            $json->has('worklist.id');
        });
    }
     */


    /**
     * Test delete worklist permanently
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
 public function test_delete_worklist_permanently()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $worklist = Worklist::factory()
            ->for($company)
            ->create();

        $response = $this->deleteJson('/api/dashboard/companies/worklists/delete', [
            'worklist_id' => $worklist->id,
            'force' => true
        ]);

        $response->assertStatus(200);

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->where('message', 'Successfully delete worklist.');
        });
    }
     */


    /**
     * Test process worklist
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
public function test_process_worklist()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $worklist = Worklist::factory()
            ->for($company)
            ->prepared()
            ->create();

        $response = $this->postJson('/api/dashboard/companies/worklists/process', [
            'worklist_id' => $worklist->id,
        ]);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->where('message', 'Successfully process worklist.');

            $json->has('worklist');
            $json->has('worklist.id');
        });
    }
     */


    /**
     * Test calculate worklist
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
     public function test_calculate_worklist()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $worklist = Worklist::factory()
            ->for($company)
            ->calculated()
            ->create();

        $response = $this->postJson('/api/dashboard/companies/worklists/calculate', [
            'worklist_id' => $worklist->id,
        ]);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->where('message', 'Successfully calculate worklist.');


            $json->has('worklist');
            $json->has('worklist.id');
        });
    }
     */


    /**
     * Test calculate worklist
     *
     * @return void
     *
     * @todo Hidden feature for next release
     * TODO: Hidden feature for next release
     *
  public function test_setting_route_management_worklist()
    {
        $this->actingAs(
            $user = User::factory()
                ->owner()
                ->create()
        );

        $company = $user->owner->company;

        $worklist = Worklist::factory()
            ->for($company)
            ->create();

        $response = $this->postJson('/api/dashboard/companies/worklists/route', [
            'worklist_id' => $worklist->id,
            'sorting_route_status' => WorklistSortingRouteStatus::Active,
            'always_sorting_route_status' => WorklistSortingRouteStatus::Active,
        ]);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) {
            $json->where('status', 'success');
            $json->where('message', 'Successfully save worklist sorting route status.');


            $json->has('worklist');
            $json->has('worklist.id');
        });
    }
     */
}
