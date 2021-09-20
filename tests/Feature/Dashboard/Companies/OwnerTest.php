<?php

namespace Tests\Feature\Dashboard\Companies;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Testing\Fluent\AssertableJson;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Models\User;
use App\Models\Owner;

class OwnerTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * Test view all company owners.
     *
     * @return void
     */
    public function test_view_all_company_owners()
    {
        $owner = Owner::whereHas('user')->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/owners';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('owners');
        });
    }

    /**
     * Test non owner view all company owners.
     *
     * @return void
     */
    public function test_non_owner_view_all_company_owners()
    {
        $nonOwnerUser = User::whereDoesntHave('owner')->first();
        $token = $nonOwnerUser->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/owners';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(403);
    }

    /**
     * Test view all inviteable owners.
     *
     * @return void
     */
    public function test_view_all_invitable_owners()
    {
        $owner = Owner::whereHas('user')->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/owners/inviteables';
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('owners.data');
        });
    }

    /**
     * Test store new owner.
     *
     * @return void
     */
    public function test_store_owner()
    {
        $owner = Owner::whereHas('user')->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $ownerData = [
            'bank_name' => 'Added  Bank',
            'bic_code' => '911',
            'bank_account' => '9988776655',
            'bank_holder_name' => 'Added Holder', 
            'address' => 'Another street',
            'house_number' => 11,
            'house_number_suffix' => 'A',
            'zipcode' => 11178,
            'city' => 'Another City',
            'province' => 'Another Province',
        ];
        $url = '/api/dashboard/companies/owners/store';
        $response = $this->withHeaders($headers)->post($url, $ownerData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->where('status', 'success');

            $json->has('owner');
            $json->has('message');
        });
    }

    /**
     * Test view owner.
     *
     * @return void
     */
    public function test_view_owner()
    {
        $owner = Owner::whereHas('user')->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $viewedOwner = Owner::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
        ];
        $url = '/api/dashboard/companies/owners/view?id=' . $viewedOwner->id;
        $response = $this->withHeaders($headers)->get($url);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('owner');
        });
    }

    /**
     * Test update owner.
     *
     * @return void
     */
    public function test_update_owner()
    {
        $owner = Owner::whereHas('user')->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $editedOwner = Owner::where('company_id', $owner->company_id)->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $ownerData = [
            'id' => $editedOwner->id,
            'bank_name' => 'Added  Bank',
            'bic_code' => '911',
            'bank_account' => '9988776655',
            'bank_holder_name' => 'Added Holder', 
            'address' => 'Another street',
            'house_number' => 11,
            'house_number_suffix' => 'A',
            'zipcode' => 11178,
            'city' => 'Another City',
            'province' => 'Another Province',
        ];
        $url = '/api/dashboard/companies/owners/store';
        $response = $this->withHeaders($headers)->post($url, $ownerData);

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->where('status', 'success');

            $json->has('owner');
            $json->has('message');
        });
    }

    /**
     * Test delete owner.
     *
     * @return void
     */
    public function test_prime_owner_delete_owner()
    {
        $primeOwner = Owner::whereHas('user')
            ->whereHas('company')
            ->primeOnly()
            ->first();
        $user = $primeOwner->user;
        $token = $user->generateToken();

        $deletedOwner = Owner::where('company_id', $primeOwner->company_id)
            ->where('is_prime_owner', false)
            ->first();
        if (! $deletedOwner) {
            $deletedOwner = Owner::create([
                'company_id' => $primeOwner->company_id,
                'is_prime_owner' => false,
                'bank_name' => 'Example',
                'bic_code' => 'Example',
                'bank_account' => 'Example',
                'bank_holder_name' => 'Example',
            ]);
        }

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $url = '/api/dashboard/companies/owners/delete';
        $response = $this->withHeaders($headers)->delete($url, ['id' => $deletedOwner->id]);

        

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) {
            $json->has('status');
            $json->where('status', 'success');

            $json->has('message');
        });
    }

    /**
     * Test delete prime owner.
     *
     * @return void
     */
    public function test_delete_prime_owner()
    {
        $owner = Owner::whereHas('user')->whereHas('company')->first();
        $user = $owner->user;
        $token = $user->generateToken();

        $primeOwner = Owner::whereHas('user')->whereHas('company')->primeOnly()->first();

        $headers = [
            'Accept' => 'application/json',
            'Authorization' => 'Bearer ' . $token,
            'Content-Type' => 'application/x-www-form-urlencoded',
        ];
        $url = '/api/dashboard/companies/owners/delete';
        $response = $this->withHeaders($headers)->delete($url, ['id' => $primeOwner->id]);

        $response->assertStatus(403);
    }
}