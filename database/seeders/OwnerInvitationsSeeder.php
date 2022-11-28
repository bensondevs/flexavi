<?php

namespace Database\Seeders;

use App\Enums\OwnerInvitation\OwnerInvitationStatus;
use App\Models\Company\Company;
use App\Models\Owner\OwnerInvitation;
use App\Models\Permission\Permission;
use Faker\Generator;
use Illuminate\Database\Seeder;
use Illuminate\Support\Arr;

class OwnerInvitationsSeeder extends Seeder
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
     * Generate owner invitations to the given company.
     *
     * @param Company $company
     * @param int $quantity
     * @return array
     */
    private function generateOwnerInvitations(Company $company, int $quantity = 5): array
    {
        $ownerInvitations = [] ;
        for ($iteration = 0; $iteration < $quantity; $iteration++) {
            array_push($ownerInvitations, [
                'id' => generateUuid(),
                'company_id' => $company->id,
                'invited_email' => $this->faker->unique()->safeEmail,
                'registration_code' => randomString(),
                'name' => $this->faker->unique()->name,
                'phone' => $this->faker->unique()->phoneNumber,
                'status' => OwnerInvitationStatus::Expired,
                'expiry_time' => $this->faker->randomElement(
                    [null , now()->copy()->addDays(rand(3, 7))]
                ),
                'permissions' => json_encode($this->permissions)
            ]);
        }
        return $ownerInvitations;
    }

    /**
     * Generate pending/active owner invitations to the given companies.
     *
     * @param Company $company
     * @param int $quantity
     * @return array
     */
    private function generatePendingOwnerInvitations(Company $company, int $quantity = 5): array
    {
        return array_map(function ($ownerInvitation) {
            return array_merge($ownerInvitation, ['status' => OwnerInvitationStatus::Active]);
        }, $this->generateOwnerInvitations($company, $quantity));
    }

    /**
     * Generate used owner invitations to the given companies.
     *
     * @param Company $company
     * @param int $quantity
     * @return array
     */
    private function generateUsedOwnerInvitations(Company $company, int $quantity = 5): array
    {
        return array_map(function ($ownerInvitation) {
            return array_merge($ownerInvitation, ['status' => OwnerInvitationStatus::Used]);
        }, $this->generateOwnerInvitations($company, $quantity));
    }

     /**
     * Generate used owner invitations to the given companies.
     *
     * @param Company $company
     * @param int $quantity
     * @return array
     */
    private function generateExpiredOwnerInvitations(Company $company, int $quantity = 5): array
    {
        return array_map(function ($ownerInvitation) {
            return array_merge($ownerInvitation, ['status' => OwnerInvitationStatus::Expired]);
        }, $this->generateOwnerInvitations($company, $quantity));
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
            array_push($ownerInvitations, $this->generatePendingOwnerInvitations($company, 10));
            array_push($ownerInvitations, $this->generateUsedOwnerInvitations($company, 10));
            array_push($ownerInvitations, $this->generateExpiredOwnerInvitations($company, 10));
        }

        foreach (
            array_chunk(Arr::flatten($ownerInvitations, 1), 100) as $chunk
        ) {
            OwnerInvitation::insert($chunk);
        }
    }
}
