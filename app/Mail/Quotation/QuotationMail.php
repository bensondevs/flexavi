<?php

namespace App\Mail\Quotation;

use App\Models\Quotation\Quotation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class QuotationMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Quotation model container
     *
     * @var Quotation
     */
    public Quotation $quotation;

    /**
     * Create a new message instance.
     *
     * @param Quotation $quotation
     */
    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation->fresh()->load('items.workService', 'customer');
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->view('mails.quotations.sent-quotation',)
            ->with([
                'quotation' => $this->quotation,
            ]);
    }
}
