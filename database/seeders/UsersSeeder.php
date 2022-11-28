<?php

namespace Database\Seeders;

use App\Models\User\User;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\User\UserRepository;
use Carbon\Carbon;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\{File, Storage};

/**
 * @deprecated Moved to Owner Seeders
 */
class UsersSeeder extends Seeder
{
    /**
     * The current UserRepository instance
     *
     * @var UserRepository
     */
    protected UserRepository $userRepository;

    /**
     * Permission repository instance container property.
     *
     * @var PermissionRepository
     */
    private PermissionRepository $permissionRepository;

    /**
     * The current Faker instance.
     *
     * @var Generator
     */
    protected mixed $faker;

    /**
     * Create a new seeder instance.
     *
     * @return void
     */
    public function __construct(UserRepository $userRepository, PermissionRepository $permissionRepository)
    {
        $this->userRepository = $userRepository;
        $this->permissionRepository = $permissionRepository;
        $this->faker = app(Generator::class);
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        // create upload directory with the right permission
        /*$path = Storage::path('users');
        if (!Storage::exists($path)) {
            Storage::makeDirectory($path);
        }*/

        // Administrator
        /*$this->userRepository->setModel(new User());
        $user = $this->userRepository->save([
            'fullname' => 'Flexavi Admin',
            'email' => 'admin@flexavi.nl',
            'password' => 'admin',
            'phone' => $this->faker->unique()->phoneNumber,
            'birth_date' => Carbon::now()->subYears(rand(20, 25)),
            'profile_picture_path' => User::placeholder(),
        ]);
        $user->verifyEmail();
        $user->assignRole('admin');

        $this->ownersSeeder();
        $this->employeesSeeder();*/

        $this->command->warn('This seeder is deprecated, skipping it...');
    }


    /**
     * Seeds the owners
     *
     * @return void
     */
    private function ownersSeeder(): void
    {
        $permissionNames = $this->permissionRepository->permissionNames();

        for ($i = 1; $i <= 5; $i++) {
            /**
             *  Owner with company and has no subscription
             */
            $this->userRepository->setModel(new User());
            $user = $this->userRepository->save([
                'fullname' => "Flexavi Owner $i",
                'email' => "owner$i@flexavi.nl",
                'password' => "owner$i",
                'phone' => $this->faker->unique()->phoneNumber,
                'birth_date' => Carbon::now()->subYears(rand(20, 25)),
                'profile_picture_path' => User::placeholder(),
            ]);
            $user->verifyEmail();
            $user->assignRole('owner');
            $user->syncPermissions($permissionNames);

            /**
             *  Owner with company and has trial subcription
             */
            $this->userRepository->setModel(new User());
            $user = $this->userRepository->save([
                'fullname' => "Flexavi Owner with active Subscription $i",
                'email' => "owner_with_trial_subscription_$i@flexavi.nl",
                'password' => "password",
                'phone' => $this->faker->unique()->phoneNumber,
                'birth_date' => Carbon::now()->subYears(rand(20, 25)),
                'profile_picture_path' => User::placeholder(),
            ]);
            $user->verifyEmail();
            $user->assignRole('owner');
            $user->syncPermissions($permissionNames);

            /**
             *  Owner with company and has active subcription
             */
            $this->userRepository->setModel(new User());
            $user = $this->userRepository->save([
                'fullname' => "Flexavi Owner with active Subscription $i",
                'email' => "owner_with_active_subscription_$i@flexavi.nl",
                'password' => "password",
                'phone' => $this->faker->unique()->phoneNumber,
                'birth_date' => Carbon::now()->subYears(rand(20, 25)),
                'profile_picture_path' => User::placeholder(),
            ]);
            $user->verifyEmail();
            $user->assignRole('owner');
            $user->syncPermissions($permissionNames);

            /**
             *  Owner with company and has expired subcription
             */
            $this->userRepository->setModel(new User());
            $user = $this->userRepository->save([
                'fullname' => "Flexavi Owner with active Subscription $i",
                'email' => "owner_with_expired_subscription_$i@flexavi.nl",
                'password' => "password",
                'phone' => $this->faker->unique()->phoneNumber,
                'birth_date' => Carbon::now()->subYears(rand(20, 25)),
                'profile_picture_path' => User::placeholder(),
            ]);
            $user->verifyEmail();
            $user->assignRole('owner');
            $user->syncPermissions($permissionNames);

            /**
             * Trashed owner with company and has no subscription
             */
            $this->userRepository->setModel(new User());
            $user = $this->userRepository->save([
                'fullname' => "Flexavi Trashed Owner With Company $i",
                'email' => "trashedowner$i@flexavi.nl",
                'password' => "trashedowner$i",
                'phone' => $this->faker->unique()->phoneNumber,
                'birth_date' => Carbon::now()->subYears(rand(20, 25)),
                'profile_picture_path' => User::placeholder(),
                'deleted_at' => now()->copy()->subDays(rand(1, 10))->toDateTimeString(),
            ]);
            $user->verifyEmail();
            $user->assignRole('owner');
            $user->syncPermissions($permissionNames);

            /**
             *  Owner without company and has no subscription
             */
            $this->userRepository->setModel(new User());
            $user = $this->userRepository->save([
                'fullname' => "Flexavi Owner Without Company $i",
                'email' => "withoutcompany$i@flexavi.nl",
                'password' => "withoutcompany$i",
                'phone' => $this->faker->unique()->phoneNumber,
                'birth_date' => Carbon::now()->subYears(rand(20, 25)),
                'profile_picture_path' => User::placeholder(),
            ]);
            $user->verifyEmail();
            $user->assignRole('owner');
            $user->syncPermissions($permissionNames);
        }
    }

    /**
     * Seeds the employees
     *
     * @return void
     */
    private function employeesSeeder(): void
    {
        // Get total number of owners
        $userOwnerCount = User::where('email', 'like', 'owner%')->count();

        for ($i = 1; $i <= $userOwnerCount * 5; $i++) {
            /**
             * Employee
             */
            $this->userRepository->setModel(new User());
            $user = $this->userRepository->save([
                'fullname' => "Flexavi Employee $i",
                'email' => "employee$i@flexavi.nl",
                'password' => "employee$i",
                'phone' => $this->faker->unique()->phoneNumber,
                'birth_date' => Carbon::now()->subYears(rand(20, 25)),
                'profile_picture_path' => User::placeholder(),
            ]);
            $user->verifyEmail();
            $user->assignRole('employee');

            /**
             * Trashed Employee
             */
            $this->userRepository->setModel(new User());
            $user = $this->userRepository->save([
                'fullname' => "Flexavi Trashed Employee $i",
                'email' => "trashedemployee$i@flexavi.nl",
                'password' => "trashedemployee$i",
                'phone' => $this->faker->unique()->phoneNumber,
                'birth_date' => Carbon::now()->subYears(rand(20, 25)),
                'profile_picture_path' => User::placeholder(),
                'deleted_at' => now()->copy()->subDays(rand(1, 10))->toDateTimeString(),
            ]);
            $user->verifyEmail();
            $user->assignRole('employee');
        }
    }
}
