<?php

namespace Database\Seeders;

use App\Enums\Role;
use App\Models\Company\Company;
use App\Models\Owner\Owner;
use App\Models\User\User;
use App\Repositories\Permission\PermissionRepository;
use Illuminate\Database\Seeder;

class OwnersSeeder extends Seeder
{
    /**
     * Permission container property.
     *
     * @var array
     */
    private array $permissions = [];

    /**
     * Generate owner instance with it's user. (without company)
     *
     * @param Company $company
     * @return Owner
     */
    private function generateOwnerWithoutCompany(): Owner
    {
        $ownerUser = User::query()->create([
            'id' => generateUuid(),
            'fullname' => 'Owner Without Company',
            'email' => 'owner_without_company@flexavi.nl',
            'email_verified_at' => now(),
            'password' => bcrypt('owner_without_company'),
        ]);
        $ownerUser->refresh();

        // Assign owner user with needed permissions
        $ownerUser->syncRoles([Role::Owner]);
        $ownerUser->syncPermissions($this->permissions);

        return Owner::query()->create([
            'id' => generateUuid(),
            'company_id' => null,
            'user_id' => $ownerUser->id,
            'is_prime_owner' => true,
        ]);
    }

    /**
     * Generate owner instance with it's user.
     *
     * @param Company $company
     * @return Owner
     */
    private function generateOwner(Company $company): Owner
    {
        // Decide the owner email counter
        $counter = User::where('email', 'like', 'owner%')->count();
        $counter++;

        $ownerUser = User::factory()->create([
            'fullname' => 'Flexavi Owner ' . $counter,
            'email' => 'owner' . $counter . '@flexavi.nl',
            'password' => bcrypt('owner' . $counter),
        ]);
        $ownerUser->refresh();

        // Assign owner user with needed permissions
        $ownerUser->syncRoles([Role::Owner]);
        $ownerUser->syncPermissions($this->permissions);

        // Create owner instance
        $oldOwner = $ownerUser->owner;
        Company::whereId($oldOwner->company_id)->forceDelete();
        Owner::whereId($oldOwner->id)->forceDelete();
        return Owner::factory()->createQuietly([
            'id' => generateUuid(),
            'company_id' => $company->id,
            'user_id' => $ownerUser->id,
            'is_prime_owner' => Owner::whereCompanyId($company->id)
                ->whereIsPrimeOwner(true)
                ->doesntExist(),
        ]);
    }

    /**
     * Seed owners to given companies.
     *
     * @param Company $company
     * @param int $quantity without main owner.
     * @return voidd
     */
    private function seedOwners(Company $company, int $quantity = 5): void
    {
        // Generate other owners
        for ($iteration = 0; $iteration < $quantity; $iteration++) {
            $this->generateOwner($company);
        }
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $this->permissions = app(PermissionRepository::class)->permissionNames();

        foreach (Company::withTrashed()->get() as $company) {
            $this->seedOwners($company, 3);
        }

        $this->generateOwnerWithoutCompany();
    }
}
