<?php

namespace App\Mail\HelpDesk;

use App\Models\HelpDesk\HelpDesk;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HelpDeskMail extends Mailable
{
    use Queueable, SerializesModels;

    private $helpDesk;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(HelpDesk $helpDesk)
    {
        $this->helpDesk = $helpDesk;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $helpDesk = $this->helpDesk;
        return $this->view('mails.help-desks.mail')->with([
            'helpDesk' => $helpDesk
        ]);
    }
}
