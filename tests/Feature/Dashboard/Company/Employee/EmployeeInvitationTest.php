<?php

namespace Tests\Feature\Dashboard\Company\Employee;

use App\Enums\Employee\EmployeeType;
use App\Http\Resources\Employee\EmployeeInvitationResource;
use App\Repositories\Permission\PermissionRepository;
use App\Models\{Employee\EmployeeInvitation};
use App\Traits\FeatureTestUsables;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Employee\EmployeeInvitationController
 *      To the tested controller class.
 */
class EmployeeInvitationTest extends TestCase
{
    use WithFaker, FeatureTestUsables;

    /**
     * Module base API URL.
     *
     * @const
     */
    const MODULE_BASE_API_URL = '/api/dashboard/companies/employees/invitations';

    /**
     * Test populate company employee invitations
     *
     * @return void
     */
    public function test_populate_company_employee_invitations(): void
    {
        $this->authenticateAsOwner();

        $response = $this->getJson(self::MODULE_BASE_API_URL);

        $response->assertOk();
        $this->assertResponseAttributeIsPaginationInstance(
            $response,
            'invitations',
        );
    }

    /**
     * Test get a company employee invitation
     *
     * @return void
     */
    public function test_get_company_employee_invitation(): void
    {
        $user = $this->authenticateAsOwner();
        $company = $user->owner->company;

        $employeeInvitation = EmployeeInvitation::factory()
            ->for($company)
            ->create();
        $response = $this->getJson(urlWithParams(self::MODULE_BASE_API_URL . '/view', [
            'id' => $employeeInvitation->id,
        ]));
        $response->assertOk();
        $response->assertJson(function (AssertableJson $json) {
            $json->has('invitation');
            $json->has('invitation.id');
        });
    }

    /**
     * Test store a company employee invitation
     *
     * @return void
     */
    public function test_store_company_employee_invitation(): void
    {
        $this->authenticateAsOwner();

        $url = self::MODULE_BASE_API_URL . '/store';

        $permissions = app(PermissionRepository::class)->permissionNames();
        $response = $this->postJson($url, [
            'invited_email' => $this->faker->safeEmail,
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date,
            'phone' => $this->faker->phoneNumber,
            'title' => random_string(10),
            'expiry_time' => Carbon::now()
                ->addDays(1)
                ->format($this->preferedDateFormat),
            'role' => EmployeeType::getRandomValue(),
            'contract_file' => UploadedFile::fake()->create(
                'document.pdf',
                100,
                'application/pdf'
            ),
            'permissions' => json_encode(
                collect($permissions)->take(5)->toArray(),
                true
            ),
        ]);

        $response->assertCreated();
        $this->assertResponseStatusSuccess($response);
        $this->assertInstanceReturnedInResponse(
            $response,
            'invitation',
            EmployeeInvitationResource::class,
        );
    }

    /**
     * Test cancel a company employee invitation
     *
     * @return void
     */
    public function test_cancel_company_employee_invitation(): void
    {
        $user = $this->authenticateAsOwner();
        $employeeInvitation = EmployeeInvitation::factory()
            ->for($user->owner->company)
            ->create();
        $response = $this->deleteJson(self::MODULE_BASE_API_URL . '/cancel', [
            'id' => $employeeInvitation->id,
        ]);
        $response->assertOk();
        $this->assertResponseStatusSuccess($response);
    }
}
