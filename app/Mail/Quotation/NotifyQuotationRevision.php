<?php

namespace App\Mail\Quotation;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

use App\Models\Quotation;

class NotifyQuotationRevision extends Mailable
{
    use Queueable, SerializesModels;

    private $quotation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Quotation $quotation)
    {
        $this->quotation = $quotation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $quotation = $this->quotation;
        return $this->view('mails.quotations.notify-quotation-revision')->with([
            'quotation' => $quotation, 
            'revised' => $quotation->getDirty()
        ]);
    }
}
