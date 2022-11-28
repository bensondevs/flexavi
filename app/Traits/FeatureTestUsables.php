<?php

namespace App\Traits;

use App\Enums\Employee\EmployeeType;
use App\Enums\Role;
use App\Models\Company\Company;
use App\Models\Employee\Employee;
use App\Models\Owner\Owner;
use App\Models\User\User;
use App\Repositories\Permission\PermissionRepository;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Testing\TestResponse;

trait FeatureTestUsables
{
    /**
     * Test company instance container property.
     *
     * @var Company|null
     */
    private ?Company $testCompany = null;

    /**
     * Test user container property.
     *
     * @var User|null
     */
    private ?User $testUser = null;

    /**
     * Get current test company.
     *
     * @return Company|null
     */
    protected function getTestCompany(): ?Company
    {
        return $this->testCompany;
    }

    /**
     * Get current test user.
     *
     * @return User
     */
    protected function getTestUser(): User
    {
        return $this->testUser;
    }

    /**
     * Force tester user relations loaded properly.
     *
     * This will enable the relation load to ensure the value works
     * properly and prevent 403 error due to relationships with company are not loaded.
     *
     * @param User $testerUser
     * @param string $type
     * @return User
     */
    protected function forceTesterUserRelationsLoaded(
        User $testerUser,
        string $type,
    ): User {
        // Force role is assigned to user
        $testerUser->syncRoles($type);

        // Force role model relationship loaded
        $roleModel = match ($type) {
            Role::Employee => new Employee(),
            Role::Owner => new Owner(),
        };
        if (!$testerUser->relationLoaded($type) or !isset($testerUser->{$type}->company_id)) {
            $testerUser->{$type} = $roleModel->whereUserId($testerUser->id)->first() ?:
                $roleModel->factory(state: ['user_id' => $testerUser->id])->create();
        }

        // Force company model relationship loaded
        $role = $testerUser->{$type} ??
            $roleModel->factory(state: ['user_id' => $testerUser->id])->create();
        $testerUser->{$type} = $role->fresh();
        if (!$role->relationLoaded('company') or !isset($role->company->id)) {
            $testerUser->{$type}->load(['company']);
        }

        return $testerUser;
    }

    /**
     * Authenticate user as owner.
     *
     * @param bool $mainOwner
     * @return User
     */
    protected function authenticateAsOwner(bool $mainOwner = true): User
    {
        $ownerUser = User::factory()->owner()->create();
        $ownerUser->refresh()->load(['owner.company']);

        $ownerUser = $this->forceTesterUserRelationsLoaded($ownerUser, Role::Owner);

        // Set owner permissions, if empty the employee will have
        // all permissions that can existed in application
        $permissions = empty($permissions) ?
            app(PermissionRepository::class)->permissionNames() :
            $permissions;
        $ownerUser->syncPermissions($permissions);

        $owner = $ownerUser->owner;
        $owner->is_prime_owner = $mainOwner;
        $owner->save();

        // Set the current test instance instrument
        $this->testUser = $ownerUser;
        $this->testCompany = $ownerUser->owner->company;

        $this->actingAs($ownerUser);

        return $ownerUser;
    }

    /**
     * Authenticate user as employee.
     *
     * @param int $employeeType
     * @param array $permissions
     * @return User
     */
    protected function authenticateAsEmployee(
        int $employeeType = EmployeeType::Administrative,
        array $permissions = [],
    ): User {
        $employeeUser = User::factory()->employee()->create();
        Company::whereHas('owners', function ($ownersQuery) use ($employeeUser) {
            $ownersQuery->where('user_id', $employeeUser->id);
        })->delete();
        Owner::whereUserId($employeeUser->id)->delete();
        $employeeUser->refresh()->load(['employee.company']);

        $employeeUser = $this->forceTesterUserRelationsLoaded(
            $employeeUser,
            Role::Employee,
        );

        // Set employee permissions, if empty the employee will have
        // all permissions that can existed in application
        $permissions = empty($permissions) ?
            app(PermissionRepository::class)->permissionNames() :
            $permissions;
        $employeeUser->syncPermissions($permissions);

        // Set employee instance
        $employee = $employeeUser->employee;
        $employee->employee_type = $employeeType;
        $employee->save();

        // Set the current test instance instrument
        $this->testUser = $employeeUser;
        $this->testCompany = $employeeUser->employee->company;

        $this->actingAs($employeeUser);

        return $employeeUser;
    }

    /**
     * Assert response contains attributes of status and message.
     *
     * The status expected is "success"
     *
     * @param TestResponse $response
     * @return void
     */
    protected function assertResponseStatusSuccess(TestResponse $response): void
    {
        // Assert JSON structure as "status" and "message" content.
        $content = $response->getOriginalContent();
        $this->assertArrayHasKey('status', $content);
        $this->assertArrayHasKey('message', $content);

        // Assert "status" attribute value is "success".
        $response->assertJson(['status' => 'success']);
    }

    /**
     * Assert response contains attributes of status and message.
     *
     * The status expected is "error"
     *
     * @param TestResponse $response
     * @return void
     */
    protected function assertResponseStatusError(TestResponse $response): void
    {
        // Assert JSON structure as "status" and "message" content.
        $response->assertJsonStructure([
            'message',
            'errors',
        ]);
    }

    /**
     * Assert response returned from the request should specified instance.
     *
     * @param TestResponse $response
     * @param string $attributeName
     * @param string $expectedInstance
     * @param array $requiredAttributes
     * @return void
     */
    protected function assertInstanceReturnedInResponse(
        TestResponse $response,
        string $attributeName,
        string $expectedInstance,
        array $requiredAttributes = ['id'],
    ): void {
        $content = $response->getOriginalContent();

        // Assert instance is included in the request
        $this->assertArrayHasKey($attributeName, $content);

        // Assert instance given in the content has attribute required
        $returnedInstance = $content[$attributeName];
        $this->assertInstanceOf($expectedInstance, $returnedInstance);

        // Assert instance contains given required attributes
        foreach ($requiredAttributes as $requiredAttribute) {
            $returnedRequiredAttribute = null ;
            foreach (explode('.', $requiredAttribute) as $attribute) {
                if (is_null($returnedRequiredAttribute)) {
                    $returnedRequiredAttribute  = tryIsset(
                        fn () => $returnedInstance->{$attribute} ?? $returnedInstance[$attribute]
                    );
                } else {
                    $returnedRequiredAttribute = tryIsset(
                        fn () => $returnedRequiredAttribute->{$attribute} ?? $returnedRequiredAttribute[$attribute]
                    );
                }
            }
            $this->assertNotNull(
                $returnedRequiredAttribute,
                'No attribute named "'.$requiredAttribute.'" does not exist.',
            );
        }
    }

    /**
     * Assert response expected attribute is pagination instance.
     *
     * @param TestResponse $response
     * @param string $attributeName
     * @param array $requiredAttributes
     * @param array $whereNotAttributes
     * @return void
     */
    protected function assertResponseAttributeIsPaginationInstance(
        TestResponse $response,
        string $attributeName,
        array $requiredAttributes = [],
        array $whereNotAttributes = [],
    ): void {
        // Assert attribute name is exist in the response attribute
        $content = $response->getOriginalContent();
        $this->assertArrayHasKey($attributeName, $content);

        // Assert JSON expected content is pagination
        $response->assertJson(function (AssertableJson $json) use (
            $attributeName,
            $requiredAttributes,
            $whereNotAttributes
        ) {
            // Ensure JSON contains array in the content
            $json->whereType($attributeName . '.data', 'array');

            // Assert pagination array pagination keys
            $json->has($attributeName . '.current_page');
            $json->has($attributeName . '.first_page_url');
            $json->has($attributeName . '.from');
            $json->has($attributeName . '.last_page');
            $json->has($attributeName . '.last_page_url');
            $json->has($attributeName . '.links');
            $json->has($attributeName . '.next_page_url');
            $json->has($attributeName . '.path');
            $json->has($attributeName . '.per_page');
            $json->has($attributeName . '.prev_page_url');
            $json->has($attributeName . '.to');
            $json->has($attributeName . '.total');

            foreach ($requiredAttributes as $requiredAttributeKey => $requiredAttributeValue) {
                is_integer($requiredAttributeKey) ?
                    $json->has("$attributeName.$requiredAttributeValue") :
                    $json->where(
                        "$attributeName.$requiredAttributeKey",
                        $requiredAttributeValue
                    );
            }

            foreach ($whereNotAttributes as $whereNotAttributeKey => $whereNotAttributeValue) {
                $json->whereNot(
                    "$attributeName.$whereNotAttributeKey",
                    $whereNotAttributeValue
                );
            }
        });
    }
}
