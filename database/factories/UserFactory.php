<?php

namespace Database\Factories;

use App\Enums\Role;
use App\Models\{Company\Company,
    Customer\Customer,
    Employee\Employee,
    Invitation\RegisterInvitation,
    Owner\Owner,
    User\User
};
use App\Repositories\Permission\PermissionRepository;
use App\Traits\FactoryDeletedState;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = User::class;

    /**
     * Indicate that the user will be created without role.
     *
     * @var bool
     */
    private bool $withoutRole = false;

    /**
     * Assigned role when the user is not.
     *
     * @var string
     */
    private string $role = 'owner';

    /**
     * Company that will be attached to the user.
     *
     * @var Company|null
     */
    private ?Company $company = null;

    /**
     * Contains the list of permissions that will be attached to the
     * user instance after creating.
     *
     * @var array
     */
    private array $permissions = [
        //
    ];

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure(): static
    {
        // create upload directory with the right permission
        $path = Storage::path('users');
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
        }

        // Skip the other steps when the developer decide to create user
        // without any assigned role to given user
        if ($this->withoutRole) {
            return $this;
        }

        return $this->afterCreating(function (User $user) {
            // Assign the user with the expected role
            $user->syncRoles([$this->role]);

            // If the role is administrator then just skip next part
            if ($this->role === Role::Admin) {
                return $this;
            }

            // Create or get company for the user
            $company = $this->company ?:
                Company::factory()->create();

            // Prepare the role instance
            $roleFactory = match ($this->role) {
                'employee' => Employee::factory(),
                'customer' => Customer::factory(),
                default => Owner::factory(),
            };
            $roleFactory->create([
                'company_id' => $company->id,
                'user_id' => $user->id,
            ]);

            // Assign the permissions accordingly
            $permissions = $this->permissions ?:
                app(PermissionRepository::class)->permissionNames();
            $user->syncPermissions($permissions);

            return $this;
        });
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return Factory
     */
    public function unverified(): Factory
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }

    /**
     * Indicate that created user will be generated without role.
     *
     * @return Factory
     */
    public function withoutRole(): Factory
    {
        $this->withoutRole = true;

        return $this;
    }

    /**
     * Indicate that the model's role is owner.
     *
     * @param ?Company $company
     * @return Factory
     */
    public function owner(?Company $company = null): Factory
    {
        // Disable without role logic
        $this->withoutRole = false;

        // Set the company, if null will be created later.
        $this->company = $company;

        // Set the role of the user.
        $this->role = 'owner';

        return $this;
    }

    /**
     * Indicate that the model's role is owner.
     *
     * @param mixed|null $company
     * @return Factory
     */
    public function employee(mixed $company = null): Factory
    {
        // Disable without role logic
        $this->withoutRole = false;

        // Set the company, if null will be created later.
        $this->company = $company;

        // Set the role of the user.
        $this->role = 'employee';

        return $this;
    }

    /**
     * Indicate that the model's registered with registration code.
     *
     * @return Factory
     */
    public function useCode(): Factory
    {
        $userEmail = $this->definition()['email'];
        $registerInvitation = RegisterInvitation::factory()
            ->create([
                'invited_email' => $userEmail,
            ])
            ->create();

        return $this->state(function (array $attributes) use (
            $registerInvitation
        ) {
            return [
                'registration_code' => $registerInvitation->registration_code,
            ];
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition(): array
    {
        return [
            'fullname' => $this->faker->name,
            'birth_date' => $this->faker->date,
            'profile_picture_path' => User::placeholder(),
            'phone' => $this->faker->unique()->phoneNumber,
            'email' => random_string(10) . $this->faker->unique()->safeEmail,
            'email_verified_at' => Carbon::now(),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'registration_code' => null,
            'remember_token' => Str::uuid(),
        ];
    }
}
