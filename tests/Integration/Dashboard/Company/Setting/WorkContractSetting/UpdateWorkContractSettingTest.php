<?php

namespace Tests\Integration\Dashboard\Company\Setting\WorkContract;

use App\Enums\Setting\WorkContract\WorkContractContentTextType;
use App\Models\User\User;
use Database\Factories\WorkContractSettingFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Testing\Fluent\AssertableJson;
use Tests\TestCase;

/**
 *  @see App\Http\Controllers\Api\Company\Setting\SettingController::update()
 *      to the tested controller
 */
class UpdateWorkContractSettingTest extends TestCase
{
    use WithFaker;

    /**
    * Base API URL container constant.
    *
    * @const
    */
    public const BASE_MODULE_URL = '/api/dashboard/companies/settings/work_contract/update';

    /**
    * Authenticate the tester user to access the endpoint.
    *
    * @test
    * @return User
    */
    private function authenticate(): User
    {
        $user = User::factory()->owner()->create();
        $company = $user->owner->company;
        $this->assertEquals($user->owner->company_id, $company->id);

        // set as main owner
        $user->owner->is_prime_owner = true;
        $user->owner->save();
        $this->assertTrue($user->owner->fresh()->is_prime_owner);

        $this->actingAs($user);

        return $user;
    }

    /**
    * Test update work_contract setting
    *
    * @return void
    */
    public function test_update_work_contract_setting(): void
    {
        $user = $this->authenticate();
        $company = $user->owner->company;

        $this->actingAs($user);

        // create work_contract setting for user's company
        $setting = WorkContractSettingFactory::new()
            ->create(['company_id' => $company->id]);

        $input = [
            'footer' => 'Footer of the work contract',
            'foreword_contents' => [
                [   // Title
                    'text' => $this->faker->word,
                    'order_index' => 1,
                    'text_type' => WorkContractContentTextType::Title
                ],
                [   // Point
                    'text' => $this->faker->text,
                    'order_index' => 1,
                    'text_type' => WorkContractContentTextType::Point
                ],
                [   // Point Two
                    'text' => $this->faker->text,
                    'order_index' => 2,
                    'text_type' => WorkContractContentTextType::Point
                ],
                [   // Sub Point
                    'text' => $this->faker->text,
                    'order_index' => 1,
                    'text_type' => WorkContractContentTextType::SubPoint
                ],
                [   // Sub Point Two
                    'text' => $this->faker->text,
                    'order_index' => 2,
                    'text_type' => WorkContractContentTextType::SubPoint
                ],
                [   // Sub Point Three
                    'text' => $this->faker->text,
                    'order_index' => 3,
                    'text_type' => WorkContractContentTextType::SubPoint
                ]
            ],
            'contract_contents' => [
                [   // Title
                    'text' => $this->faker->word,
                    'order_index' => 1,
                    'text_type' => WorkContractContentTextType::Title
                ],
                [   // Point
                    'text' => $this->faker->text,
                    'order_index' => 1,
                    'text_type' => WorkContractContentTextType::Point
                ],
                [   // Point Two
                    'text' => $this->faker->text,
                    'order_index' => 2,
                    'text_type' => WorkContractContentTextType::Point
                ],
                [   // Sub Point
                    'text' => $this->faker->text,
                    'order_index' => 1,
                    'text_type' => WorkContractContentTextType::SubPoint
                ],
                [   // Sub Point Two
                    'text' => $this->faker->text,
                    'order_index' => 2,
                    'text_type' => WorkContractContentTextType::SubPoint
                ],
                [   // Sub Point Three
                    'text' => $this->faker->text,
                    'order_index' => 3,
                    'text_type' => WorkContractContentTextType::SubPoint
                ]
            ],
            'signature' => UploadedFile::fake()->image('signature.png'),
            'signature_name' => $this->faker->name,
        ];

        $response = $this->putJson(
            self::BASE_MODULE_URL,
            $input
        );

        $response->assertStatus(200);
        $response->assertJson(function (AssertableJson $json) use ($user) {
            $json->has('status');
            $json->has('message');
        });
    }
}
