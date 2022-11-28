<?php

namespace Tests\Unit\Factories\Company;

use App\Models\Company\Company;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class CompanyTest extends TestCase
{
    use WithFaker;

    /**
     * Test create a company instance
     *
     * @return void
     */
    public function test_create_company_instance()
    {
        // make an instance
        $company = Company::factory()->create();

        // assert the instance
        $this->assertNotNull($company);
        $this->assertModelExists($company);
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'company_name' => $company->company_name,
            'email' => $company->email,
            'phone_number' => $company->phone_number,
            'vat_number' => $company->vat_number,
            'commerce_chamber_number' => $company->commerce_chamber_number,
            'company_website_url' => $company->company_website_url,
        ]);
    }

    /**
     * Test multiple company instances
     *
     * @return void
     */
    public function test_create_multiple_company_instances()
    {
        // make the instances
        $count = 10;
        $companies = Company::factory($count)->create();

        // assert the instances
        $this->assertTrue(count($companies) === $count);
    }

    /**
     * Test update a company instance
     *
     * @return void
     */
    public function test_update_company_instance()
    {
        // make an instance
        $company = Company::factory()->create();

        // assert the instance
        $this->assertNotNull($company);
        $this->assertModelExists($company);
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'company_name' => $company->company_name,
            'email' => $company->email,
            'phone_number' => $company->phone_number,
            'vat_number' => $company->vat_number,
            'commerce_chamber_number' => $company->commerce_chamber_number,
            'company_website_url' => $company->company_website_url,
        ]);

        // generate dummy data
        $companyName = $this->faker->company();
        $email = $this->faker->safeEmail();
        $phoneNumber = $this->faker->phoneNumber();
        $vatNumber = $this->faker->randomNumber(5, true);
        $commerceChamberNumber = rand(1, 100);
        $companyWebsiteUrl = $this->faker->url();

        // update instance
        $company->update([
            'company_name' => $companyName,
            'email' => $email,
            'phone_number' => $phoneNumber,
            'vat_number' => $vatNumber,
            'commerce_chamber_number' => $commerceChamberNumber,
            'company_website_url' => $companyWebsiteUrl,
        ]);

        // assert the updated instance
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'company_name' => $companyName,
            'email' => $email,
            'phone_number' => $phoneNumber,
            'vat_number' => $vatNumber,
            'commerce_chamber_number' => $commerceChamberNumber,
            'company_website_url' => $companyWebsiteUrl,
        ]);
    }

    /**
     * Test soft delete a company instance
     *
     * @return void
     */
    public function test_soft_delete_company_instance()
    {
        // make an instance
        $company = Company::factory()->create();

        // assert the instance
        $this->assertNotNull($company);
        $this->assertModelExists($company);
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'company_name' => $company->company_name,
            'email' => $company->email,
            'phone_number' => $company->phone_number,
            'vat_number' => $company->vat_number,
            'commerce_chamber_number' => $company->commerce_chamber_number,
            'company_website_url' => $company->company_website_url,
        ]);

        // soft delete the instance
        $company->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($company);
    }

    /**
     * Test restore a trashed company instance
     *
     * @return void
     */
    public function test_restore_trashed_company_instance()
    {
        // make an instance
        $company = Company::factory()->create();

        // assert the instance
        $this->assertNotNull($company);
        $this->assertModelExists($company);
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'company_name' => $company->company_name,
            'email' => $company->email,
            'phone_number' => $company->phone_number,
            'vat_number' => $company->vat_number,
            'commerce_chamber_number' => $company->commerce_chamber_number,
            'company_website_url' => $company->company_website_url,
        ]);

        // soft delete the instance
        $company->delete();

        // assert the soft deleted instance
        $this->assertSoftDeleted($company);

        // restore the trashed instance
        $company->restore();

        // assert the restored instance
        $this->assertNotSoftDeleted($company);
    }
}
