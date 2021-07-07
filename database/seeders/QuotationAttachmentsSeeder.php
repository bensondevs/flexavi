<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Quotation;
use App\Models\QuotationAttachment;

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
                    'quotation_id' => $quotation->id,
                    'name' => 'Fake Quotation Attachment',
                    'description' => 'This is seeder generated attachment',
                    'attachment_path' => '/uploads/quotations/attachments/dummy.pdf',
                    'created_at' => carbon()->now(),
                    'updated_at' => carbon()->now(),
                ]);
            }
        }

        QuotationAttachment::insert($rawAttachments);
    }
}
