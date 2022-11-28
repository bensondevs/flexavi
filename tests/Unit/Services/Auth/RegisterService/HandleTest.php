<?php

namespace Tests\Unit\Services\Auth\RegisterService;

use App\Enums\User\UserIdCardType;
use App\Enums\User\UserSocialiteAccountType;
use App\Models\Owner\OwnerInvitation;
use App\Models\Permission\Permission;
use App\Repositories\Auths\AuthRepository;
use App\Repositories\Permission\PermissionRepository;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * @see \App\Services\Auth\RegisterService::handle()
 *      To the tested service method.
 */
class HandleTest extends RegisterServiceTest
{
    use WithFaker;

    /**
     * Generate user data for testing.
     *
     * @return array
     */
    private function registerData(): array
    {
        return [
            'user' => [
                'fullname' => $this->faker->name,
                'password' => 'secret',
                'email' => random_string(5) . '@exclolab.com',
                'birth_date' => $this->faker->date(),
                'id_card_type' => UserIdCardType::NationalIdCard,
                'id_card_number' => random_string(15),
            ],

            'address' => [
                'address' => $this->faker->address,
                'house_number' => rand(10, 100),
                'house_number_suffix' => 'A',
                'zipcode' => rand(10000, 99999),
                'city' => $this->faker->city,
                'province' => $this->faker->city,
            ],
        ];
    }

    /**
     * Ensure the method returns AuthRepository instance with success response.
     *
     * @test
     * @return void
     */
    public function it_returns_auth_repository_with_success_response_and_create_user_record(): void
    {
        $registerData = $this->registerData();
        $return = $this->registerService(true)->handle($registerData);

        $this->assertInstanceOf(AuthRepository::class, $return);
        $this->assertEquals('success', $return->status);

        $user = $return->getModel()->fresh();
        $this->assertDatabaseHas('users', ['email' => $user->email]);
        $userData = $registerData['user'];
        $this->assertEquals($userData['fullname'], $user->fullname);
        $this->assertEquals($userData['email'], $user->email);
    }

    /**
     * Ensure the method creates address record to registering user.
     *
     * @test
     * @return void
     */
    public function it_creates_user_address_record(): void
    {
        $registerData = $this->registerData();
        $return = $this->registerService(true)->handle($registerData);
        $user = $return->getModel()->fresh();

        $this->assertDatabaseHas('addresses', array_merge([
            'addressable_type' => get_class($user->role_model),
            'addressable_id' => $user->role_model->id,
        ], $registerData['address']));
    }

    /**
     * Ensure the user that register on it owns turned to main owner and give
     * all permissions to do anything to company records.
     *
     * @test
     * @return void
     */
    public function it_sets_self_register_user_to_owner_and_give_it_all_permissions(): void
    {
        $registerData = $this->registerData();
        $return = $this->registerService(true)->handle($registerData);
        $user = $return->getModel()->fresh();

        // Assert user is main owner
        $owner = $user->owner;
        $this->assertNotNull($owner);
        $this->assertTrue($owner->isMainOwner());

        $permissionNames = app(PermissionRepository::class)
            ->permissionNames();
        $this->assertTrue($user->hasAllPermissions($permissionNames));
    }

    /**
     * Ensure the invited owner can register as ordinary owner and get permission as given.
     *
     * @TODO The register service buggy on this case
     * @test
     * @return void
     */
    public function it_sets_invited_owner_permissions_according_to_given_permissions_in_invitation(): void
    {
        $givenPermissions = Permission::take(rand(10, 20))
            ->inRandomOrder()
            ->get();
        $givenPermissionIds = $givenPermissions->pluck('id')->toArray();
        $registerData = $this->registerData();
        $registerData['invitation'] = OwnerInvitation::factory()
            ->create(['permissions' => json_encode($givenPermissionIds)]);
        $return = $this->registerService(true)->handle($registerData);
        $user = $return->getModel()->fresh();

        // Assert user is not main owner
        $owner = $user->owner;
        $this->assertNotNull($owner);
        $this->assertFalse($owner->isMainOwner());

        $givenPermissionNames = $givenPermissions->pluck('name')->toArray();
        $this->assertTrue($user->hasAllPermissions($givenPermissionNames));

        $notGivenPermissionNames = Permission::whereNotIn('id', $givenPermissionIds)
            ->pluck('name')
            ->toArray();
        $this->assertFalse($user->hasAnyPermission($notGivenPermissionNames));
    }

    /**
     * Ensure the method can handle socialite registration.
     *
     * @test
     * @return void
     */
    public function it_can_handle_socialite_register_correctly(): void
    {
        $registerData = $this->registerData();
        $registerData['socialite'] = [
            'type' => UserSocialiteAccountType::Google,
            'vendor_user_id' => rand(10000, 99999),
        ];
        $return = $this->registerService(true)->handle($registerData);
        $user = $return->getModel()->fresh();

        $this->assertDatabaseHas('user_socialite_accounts', [
            'user_id' => $user->id,
            'vendor_user_id' => $registerData['socialite']['vendor_user_id'],
        ]);
    }
}
