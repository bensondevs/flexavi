<?php

namespace Tests\Integration\Dashboard\Company\Employee\EmployeeInvitation;

use App\Enums\Employee\EmployeeType;
use App\Http\Resources\Employee\EmployeeInvitationResource;
use App\Jobs\Employee\SendEmployeeInvitation as EmployeeInvitationJob;
use App\Models\Employee\EmployeeInvitation;
use App\Models\Permission\Permission;
use App\Models\User\User;
use App\Repositories\Permission\PermissionRepository;
use App\Traits\FeatureTestUsables;
use Database\Factories\EmployeeInvitationFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

/**
 * @see \App\Http\Controllers\Api\Company\Employee::EmployeeInvitationController
 *      to the tested controller
 */
class StoreEmployeeInvitationTest extends TestCase
{
    use WithFaker;
    use FeatureTestUsables;

    /**
     * Feature API URL.
     *
     * @const
     */
    public const BASE_MODULE_URL = '/api/dashboard/companies/employees/invitations/store';

    /**
     * Prepare testing input.
     *
     * @return array
     */
    private function prepareTestingInput(): array
    {
        $testCompany = $this->getTestCompany();
        $permissions = app(PermissionRepository::class)->permissionNames();

        return [
            'company_id' => $testCompany->id,
            'invited_email' => $this->faker->safeEmail,
            'title' => $this->faker->word,
            'name' => $this->faker->name,
            'birth_date' => $this->faker->date,
            'phone' => $this->faker->phoneNumber,
            'expiry_time' => now()->addDays(1)->format($this->preferedDateFormat),
            'role' => EmployeeType::getRandomValue(),
            'contract_file' => UploadedFile::fake()->create(
                'document.pdf',
                100,
                'application/pdf'
            ),
            'permissions' => collect($permissions)
                ->random(rand(1, count($permissions)))
                ->toArray(),
        ];
    }

    /**
    * Test with complex things and flows
    *
    * @return void
    */
    public function test_do_complex_things(): void
    {
        Queue::fake();
        $user = $this->authenticateAsOwner();

        // invitation data
        $input = $this->prepareTestingInput();

        // make a request to create and send invitation
        $response = $this->postJson(self::BASE_MODULE_URL, $input)
            ->assertStatus(201);
        $this->assertResponseStatusSuccess($response);
        $this->assertInstanceReturnedInResponse($response, 'invitation', EmployeeInvitationResource::class);

        $invitation = EmployeeInvitation::whereInvitedEmail($input['invited_email'])
            ->whereName($input['name'])
            ->wherePhone($input['phone'])
            ->first();

        // ensure that the invitation is created after the api request
        $this->assertNotNull($invitation);

        Queue::assertPushed(
            EmployeeInvitationJob::class,
            function ($job) use ($invitation) {
                return $job->employeeInvitation->id === $invitation->id;
            }
        );

        // ensure that invited user instace is not created before the invitation has been accepted by the invited user
        $this->assertNull($invitation->invitedUser);

        // make another request to accept the invitation
        $this->get(
            "/api/dashboard/companies/employees/invitations/accept?code=$invitation->registration_code"
        )->assertStatus(302);

        // after the request was done ensure that the invited user instance is created
        $invitedUser = $invitation->fresh()->invitedUser;
        $this->assertNotNull($invitedUser);

        // get the permission names of the created invited user instance
        $invitedUserPermissionNames = $invitedUser->getPermissionNames()->toArray();
        $this->assertIsArray($invitedUserPermissionNames);

        // ensure that the created invited user instance has permissions
        foreach ($input['permissions'] as  $permissionNames) {
            $this->assertContains($permissionNames, $invitedUserPermissionNames);
        }

        // ensure length equals
        $this->assertEquals(
            count($input['permissions']),
            count($invitedUserPermissionNames)
        );
    }

    /**
     * Test invite employee to the company
     *
     * @return void
     */
    public function test_invite_employee_to_the_company(): void
    {
        Queue::fake();

        $this->authenticateAsOwner();

        $input = $this->prepareTestingInput();
        $response = $this->postJson(self::BASE_MODULE_URL, $input);

        $this->assertDatabaseHas(
            (new EmployeeInvitation())->getTable(),
            Arr::only($input, ['invited_email', 'name', 'phone', 'role']),
        );

        $response->assertCreated();
        $this->assertResponseStatusSuccess($response);
        $this->assertInstanceReturnedInResponse(
            $response,
            'invitation',
            EmployeeInvitationResource::class,
        );

        Queue::assertPushed(EmployeeInvitationJob::class, function ($job) use ($input) {
            return $job->employeeInvitation->invited_email === $input['invited_email'];
        });
    }

    /**
     * Test reinvite employee that was invited but the invitation is already expired
     *
     * @return void
     */
    public function test_reinvite_employee_that_was_invited_but_the_invitation_is_already_expired(): void
    {
        Queue::fake();

        // Authenticate user as owner
        $this->authenticateAsOwner();

        // Prepare input data for endpoint parameters
        $testCompany = $this->getTestCompany();
        $input = $this->prepareTestingInput();

        // Prepare expired invitation
        $expiredInvitation = EmployeeInvitationFactory::new()
            ->expired()
            ->create($input);

        // Make request to the endpoint
        $response = $this->postJson(self::BASE_MODULE_URL, $input);

        // Assert the response given is success
        $response->assertCreated();
        $this->assertResponseStatusSuccess($response);
        $this->assertInstanceReturnedInResponse(
            $response,
            'invitation',
            EmployeeInvitationResource::class,
        );

        // Assert database content should be created.
        $createdInvitation = EmployeeInvitation::where('id', '!=', $expiredInvitation->id)
            ->whereCompanyId($testCompany->id)
            ->whereInvitedEmail($input['invited_email'])
            ->whereName($input['name'])
            ->first();
        $this->assertNotNull($createdInvitation);

        // Assert queue pushed correctly
        Queue::assertPushed(
            EmployeeInvitationJob::class,
            function ($job) use ($createdInvitation) {
                return $job->employeeInvitation->id === $createdInvitation->id;
            }
        );
    }

    /**
     * Test should fail when invite employee that has already active invitation
     *
     * @return void
     */
    public function test_should_fail_when_invite_employee_that_has_already_active_invitation(): void
    {
        // Authenticate user as owner
        $this->authenticateAsOwner();

        // Prepare testing input
        $input = $this->prepareTestingInput();

        // create an already created active invitation
        EmployeeInvitationFactory::new()->active()->create($input);

        $response = $this->postJson(self::BASE_MODULE_URL, $input);

        $response->assertStatus(422);
        $this->assertResponseStatusError($response);
    }


    /**
     * Test should fail when inviting already registered employee
     *
     * @return void
     */
    public function test_should_fail_when_inviting_already_registered_employee(): void
    {
        // Authenticate user as owner
        $this->authenticateAsOwner();

        // Prepare testing input and make request
        $input = $this->prepareTestingInput();

        // Prepare already registered user
        $alreadyRegisteredUser = User::factory(state: ['email' => $input['invited_email']])
            ->employee()
            ->create();

        // Make request and assert status to 422
        $response = $this->postJson(self::BASE_MODULE_URL, $input);
        $response->assertStatus(422);

        // Assert response status error
        $this->assertResponseStatusError($response);

        // Assert no database record is created
        $this->assertDatabaseMissing((new EmployeeInvitation())->getTable(), [
            'invited_email' => $alreadyRegisteredUser->email,
        ]);
    }
}
