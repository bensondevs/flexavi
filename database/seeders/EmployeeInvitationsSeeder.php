<?php

namespace Database\Seeders;

use App\Enums\Employee\EmployeeType;
use App\Enums\EmployeeInvitation\EmployeeInvitationStatus;
use App\Models\Company\Company;
use App\Models\Employee\EmployeeInvitation;
use App\Models\Permission\Permission;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class EmployeeInvitationsSeeder extends Seeder
{
    /**
     * Permission container property.
     *
     * @var array
     */
    private array $permissions = [];

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
    public function __construct()
    {
        $this->faker = app(Generator::class);
    }

    /**
    * Generate employee invitations to the given company.
    *
    * @param Company $company
    * @param int $quantity
    * @return array
    */
    private function generateEmployeeInvitations(Company $company, int $quantity = 5): array
    {
        $ownerInvitations = [] ;
        for ($iteration = 0; $iteration < $quantity; $iteration++) {
            array_push($ownerInvitations, [
                'id' => generateUuid(),
                'company_id' => $company->id,
                'registration_code' => randomString(),
                'invited_email' => $this->faker->unique()->safeEmail,
                'name' => $this->faker->unique()->name,
                'birth_date' => $this->faker->date,
                'phone' => $this->faker->unique()->phoneNumber,
                'role' => $this->faker->randomElement([
                    EmployeeType::Administrative,
                    EmployeeType::Roofer,
                ]),
                'status' => EmployeeInvitationStatus::Active,
                'expiry_time' => $this->faker->randomElement(
                    [null , now()->copy()->addDays(rand(3, 7))]
                ),
                'permissions' => json_encode($this->permissions),
            ]);
        }
        return $ownerInvitations;
    }

    /**
     * Generate pending/active employee invitations to the given company.
     *
     * @param Company $company
     * @param int $quantity
     * @return array
     */
    private function generatePendingEmployeeInvitations(Company $company, int $quantity = 5): array
    {
        return array_map(function ($ownerInvitation) {
            return array_merge($ownerInvitation, ['status' => EmployeeInvitationStatus::Active]);
        }, $this->generateEmployeeInvitations($company, $quantity));
    }

    /**
     * Generate used employee invitations to the given company.
     *
     * @param Company $company
     * @param int $quantity
     * @return array
     */
    private function generateUsedEmployeeInvitations(Company $company, int $quantity = 5): array
    {
        return array_map(function ($employeeInvitation) {
            return array_merge($employeeInvitation, ['status' => EmployeeInvitationStatus::Used]);
        }, $this->generateEmployeeInvitations($company, $quantity));
    }

    /**
     * Generate expired employee invitations to the given company.
     *
     * @param Company $company
     * @param int $quantity
     * @return array
     */
    private function generateExpiredEmployeeInvitations(Company $company, int $quantity = 5): array
    {
        return array_map(function ($employeeInvitation) {
            return array_merge($employeeInvitation, ['status' => EmployeeInvitationStatus::Expired]);
        }, $this->generateEmployeeInvitations($company, $quantity));
    }


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->permissions = Permission::take(rand(10, 20))->inRandomOrder()->get()->pluck('id')->toArray();

        $ownerInvitations = [] ;
        foreach (Company::withTrashed()->get()  as $company) {
            array_push($ownerInvitations, $this->generatePendingEmployeeInvitations($company, 10));
            array_push($ownerInvitations, $this->generateUsedEmployeeInvitations($company, 10));
            array_push($ownerInvitations, $this->generateExpiredEmployeeInvitations($company, 10));
        }

        foreach (
            array_chunk(Arr::flatten($ownerInvitations, 1), 100) as $chunk
        ) {
            EmployeeInvitation::insert($chunk);
        }
    }
}
