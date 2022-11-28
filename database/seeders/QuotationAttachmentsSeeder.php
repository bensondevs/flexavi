<?php

namespace Database\Seeders;

use App\Models\Quotation\Quotation;
use App\Models\Quotation\QuotationAttachment;
use Illuminate\Database\Seeder;

class QuotationAttachmentsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $quotations = Quotation::all();

        $rawAttachments = [];
        foreach ($quotations as $index => $quotation) {
            for ($amount = 0; $amount < rand(2, 5); $amount++) {
                array_push($rawAttachments, [
                    'id' => generateUuid(),
                    'company_id' => $quotation->company_id,
                    'quotation_id' => $quotation->id,
                    'name' => 'Fake Quotation Attachment',
                    'description' => 'This is seeder generated attachment',
                    'attachment_path' => '/uploads/quotations/attachments/dummy.pdf',
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ]);
            }
        }

        foreach (array_chunk($rawAttachments, 25) as $chunk) {
            QuotationAttachment::insert($chunk);
        }
    }
}
