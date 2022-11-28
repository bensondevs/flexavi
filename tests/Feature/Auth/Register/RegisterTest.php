<?php

namespace Tests\Feature\Auth\Register;

use App\Models\Address\Address;
use App\Models\Company\Company;
use App\Models\Invitation\RegisterInvitation;
use App\Models\Owner\Owner;
use App\Models\Owner\OwnerInvitation;
use App\Models\Permission\Permission;
use App\Models\User\User;
use App\Repositories\Permission\PermissionRepository;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

class RegisterTest extends TestCase
{
    use WithFaker;

    /**
     * Test register as an owner without profile picture
     *
     * @return void
     */
    public function test_register_owner_without_profile_picture(): void
    {
        $invitation = RegisterInvitation::factory()
            ->owner()
            ->create();
        $data = [
            'invitation_code' => $invitation->registration_code,
            'fullname' => 'Test User',
            'birth_date' => '1998-05-05',
            'phone' => '99999999999',
            'address' => 'St. Road Way 123',
            'email' => $this->faker->email,
            'password' => 'AnotherPassword123!',
            'confirm_password' => 'AnotherPassword123!',
            'house_number' => 12,
            'zipcode' => 712711,
            'city' => 'Some City',
            'province' => 'Some Province',
        ];
        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });

        $this->assertDatabaseHas((new User())->getTable(), [
            'email' => $data['email'],
            'email_verified_at' => null
        ]);

        $this->assertDatabaseHas((new Address())->getTable(), [
            'address' => $data['address'],
            'zipcode' => $data['zipcode']
        ]);
    }

    /**
     * Test register as an owner
     *
     * @return void
     */
    public function test_register_owner(): void
    {
        $invitation = RegisterInvitation::factory()
            ->owner()
            ->create();


        $data = [
            'invitation_code' => $invitation->registration_code,
            'fullname' => 'Test User',
            'birth_date' => '1998-05-05',
            'phone' => '99999999999',
            'address' => 'St. Road Way 123',
            'email' => $this->faker->email,
            'password' => 'AnotherPassword123!',
            'confirm_password' => 'AnotherPassword123!',
            'profile_picture' => UploadedFile::fake()
                ->image('image.png', 100, 100)
                ->size(100),
            'house_number' => 12,
            'zipcode' => 712711,
            'city' => 'Some City',
            'province' => 'Some Province',
        ];
        $response = $this->postJson('/api/auth/register', $data);
        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });

        $user = User::where('email', $data['email'])->first();

        $user = $user->fresh();

        $invitationable = $invitation->invitationable;

        $this->assertTrue($user->hasAllPermissions(Permission::whereIn('id', $invitationable->permissions)->get()));

        $this->assertDatabaseHas((new User())->getTable(), [
            'email' => $data['email'],
            'email_verified_at' => null
        ]);

        $this->assertDatabaseHas((new Address())->getTable(), [
            'address' => $data['address'],
            'zipcode' => $data['zipcode']
        ]);

        $this->assertDatabaseHas((new Owner())->getTable(), [
            'user_id' => $user->id,
            'company_id' => $invitationable->company_id,
        ]);

        $this->assertTrue($user->hasCompany());

    }

    /**
     * Test register as an employee
     *
     * @return void
     */
    public function test_register_employee(): void
    {
        $invitation = RegisterInvitation::factory()
            ->employee()
            ->create();
        $data = [
            'invitation_code' => $invitation->registration_code,
            'fullname' => 'Test User',
            'birth_date' => '1998-05-05',
            'phone' => '99999999999',
            'address' => 'St. Road Way 123',
            'email' => $this->faker->email,
            'password' => 'AnotherPassword123!',
            'confirm_password' => 'AnotherPassword123!',
            'profile_picture' => UploadedFile::fake()
                ->image('image.png', 100, 100)
                ->size(100),
            'house_number' => 12,
            'zipcode' => 712711,
            'city' => 'Some City',
            'province' => 'Some Province',
        ];
        $response = $this->postJson('/api/auth/register', $data);

        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });


        $this->assertDatabaseHas((new User())->getTable(), [
            'email' => $data['email'],
            'email_verified_at' => null
        ]);

        $this->assertDatabaseHas((new Address())->getTable(), [
            'address' => $data['address'],
            'zipcode' => $data['zipcode']
        ]);
    }

    /**
     * Test register without invitation code
     *
     * @return void
     */
    public function test_register_without_invitation_code(): void
    {
        $data = [
            'fullname' => 'Test User',
            'birth_date' => '1998-05-05',
            'phone' => '99999999999',
            'email' => 'test' . generateUuid() . '@useronline.com',
            'password' => 'AnotherPassword123!',
            'confirm_password' => 'AnotherPassword123!',
            'profile_picture' => UploadedFile::fake()
                ->image('image.png', 100, 100)
                ->size(100),
            'address' => 'St. Road Way 123',
            'house_number' => 12,
            'zipcode' => 712711,
            'city' => 'Some City',
            'province' => 'Some Province',
        ];
        $response = $this->postJson('/api/auth/register', $data);
        $response->assertStatus(201);

        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->has('message');
        });

        $user = User::where('email', $data['email'])->first();

        $this->assertTrue(
            $user->hasAllPermissions(app(PermissionRepository::class)->permissionNames())
        );

        $this->assertDatabaseHas((new User())->getTable(), [
            'email' => $data['email'],
            'email_verified_at' => null
        ]);

        $this->assertDatabaseHas((new Address())->getTable(), [
            'address' => $data['address'],
            'zipcode' => $data['zipcode']
        ]);

        $this->assertDatabaseHas((new Company())->getTable(), [
            'company_name' => $data['fullname'],
            'email' => $data['email'],
        ]);

        $this->assertTrue($user->hasCompany());

        $this->assertUserCompanyIsInTrial($user);
    }

    /**
     * Assert user company is in trial
     *
     * @param User $user
     * @return void
     */
    private function assertUserCompanyIsInTrial(User $user): void
    {
        $company = $user->company;
        $this->assertTrue($company->onGenericTrial());
    }

    /**
     * Test find register invitation code
     *
     * @return void
     */
    public function test_find_register_invitation_code(): void
    {
        $ownerInvitation = OwnerInvitation::factory()->create();
        $response = $this->getJson('/api/auth/registration_code?code=' . $ownerInvitation->registration_code);
        $response->assertSuccessful();
        $response->assertJson(function (AssertableJson $json) {
            $json->has('invitation');
            $json->has('invitation.registration_code');
        });
    }
}
