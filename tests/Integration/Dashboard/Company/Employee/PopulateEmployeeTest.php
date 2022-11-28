<?php

namespace Tests\Integration\Dashboard\Company\Employee;

use App\Enums\Employee\EmployeeType;
use App\Enums\Employee\EmploymentStatus;
use App\Rules\NumberMultiplyOf;
use App\Traits\FeatureTestUsables;
use Database\Factories\EmployeeFactory;
use Database\Factories\UserFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Company\Employee\EmployeeController::companyEmployees()
 *      to the tested controller method
 */
class PopulateEmployeeTest extends TestCase
{
    use WithFaker;
    use FeatureTestUsables;

    /**
     * Module base API URL.
     *
     * @const
     */
    public const MODULE_BASE_URL = '/api/dashboard/companies/employees';

    /**
    * Test populate employee with configured per page pagination
    *
    * @return void
    */
    public function test_populate_employees_per_page_is_twelve(): void
    {
        $user = $this->authenticateAsOwner(true);

        $perPage = 12 ; // 12 per page
        $response = $this->getJson(self::MODULE_BASE_URL)->assertStatus(200);

        $this->assertResponseAttributeIsPaginationInstance($response, 'employees');

        $response->assertJson(function (AssertableJson $json) use ($perPage) {
            $json->where('employees.per_page', $perPage);
        });
    }

    /**
    * Test populate employees with seted query parameter "per_page" that is not multiplication of four
    * it should return error response
    *
    * @return void
    */
    public function test_populate_employees_per_page_not_multiplication_of_four_should_error(): void
    {
        $this->authenticateAsOwner(true);

        $perPage = 2 ; // $perPage not multiplication of four
        $response = $this->getJson(self::MODULE_BASE_URL."?per_page=$perPage")->assertStatus(422);

        $this->assertResponseStatusError($response);
        $this->assertStringContainsStringIgnoringCase(
            'The per page is not multiply of 4',
            $response->json('message')
        );
    }

    /**
    * Test populate employee by filter
    *
    * @return void
    */
    public function test_populate_employees_by_filter(): void
    {
        $user = $this->authenticateAsOwner(true);

        $company = $user->owner->company;

        $inactiveRoofer = EmployeeFactory::new()
            ->for($company)
            ->inactive()
            ->roofer()
            ->count(2)->create();
        $activeAdministrator = EmployeeFactory::new()
            ->for($company)
            ->active()
            ->administrative()
            ->count(3)->create();

        $response = $this->getJson(
            self::MODULE_BASE_URL .
            '?status=' . EmploymentStatus::Active .
            '&type=' . EmployeeType::Administrative
        )->assertStatus(200);

        $this->assertResponseAttributeIsPaginationInstance($response, 'employees');

        $response->assertJson(function (AssertableJson $json) use ($activeAdministrator) {
            for ($i=0; $i < $activeAdministrator->count() ; $i++) {
                $json->where("employees.data.$i.employee_type", EmployeeType::Administrative);
                $json->where("employees.data.$i.employment_status", EmploymentStatus::Active);
                $json->has("employees.data.$i.user.profile_picture");
                $json->has("employees.data.$i.deleted_at");
            }
            $json->where('employees.total', $activeAdministrator->count());
        });
    }

    /**
    * Test populate employee by search keyword
    *
    * @return void
    */
    public function test_populate_employees_by_search_keyword(): void
    {
        $user = $this->authenticateAsOwner(true);

        $company = $user->owner->company;

        $keyword = "Alexandra";
        $matchedKeywordEmployee = (function () use ($company, $keyword) {
            $user = UserFactory::new()->create(['fullname' => $keyword]);
            // delete role on the user
            $user->owner ? $user->owner->delete() : null;
            $user->employee ? $user->employee->delete() : null;

            return  EmployeeFactory::new()
                ->for($company)
                ->for($user)
                ->create();
        })();

        $unmatchedKeywordEmployee = EmployeeFactory::new()->for($company)->create();

        $response = $this->getJson(self::MODULE_BASE_URL."?keyword=$keyword")->assertStatus(200);

        $this->assertResponseAttributeIsPaginationInstance($response, 'employees');

        $response->assertJson(function (AssertableJson $json) use ($matchedKeywordEmployee) {
            $json->where("employees.data.0.id", $matchedKeywordEmployee->id);
            $json->has("employees.data.0.user.profile_picture");
            $json->has("employees.data.0.deleted_at");
        });
    }
}
