<?php

namespace App\Mail\Quotation;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Quotation;

class QuotationMail extends Mailable
{
    use Queueable, SerializesModels;

    private $quotation;
    private $text;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Quotation $quotation, string $text = '')
    {
        $this->quotation = $quotation;
        $this->text = $text;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mails.quotations.sent-quotation')
            ->with([
                'quotation' => $this->quotation,
                'text' => $this->text,
            ]);
    }
}