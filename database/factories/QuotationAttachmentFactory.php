<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Traits\FactoryDeletedState;

use App\Models\{ 
    Company, 
    Quotation, 
    QuotationAttachment as Attachment
};

class QuotationAttachmentFactory extends Factory
{
    use FactoryDeletedState;

    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Attachment::class;

    /**
     * Configure the model factory.
     *
     * @return $this
     */
    public function configure()
    {
        return $this->afterMaking(function (Attachment $attacment) {
            if (! $attachment->quotation_id) {
                $quotation = Quotation::factory()->create([
                    'company_id' => $attacment->company_id
                ]);
                $attacment->quotation()->associate($quotation);
            }
        });
    }

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $faker = $this->faker;
        return [
            'name' => $faker->word(),
            'description' => $faker->word(),
            'attachment_path' => $faker->image(
                storage_path('app/public/uploads/quotations/attachments'), 
                400, 
                300, 
                null, 
                false
            );
        ];
    }
}
