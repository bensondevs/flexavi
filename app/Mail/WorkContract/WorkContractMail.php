<?php

namespace App\Mail\WorkContract;

use App\Models\WorkContract\WorkContract;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WorkContractMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Quotation model container
     *
     * @var WorkContract
     */
    private WorkContract $workContract;

    /**
     * Create a new message instance.
     *
     * @param WorkContract $workContract
     */
    public function __construct(WorkContract $workContract)
    {
        $this->workContract = $workContract;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): static
    {
        return $this->view('mails.work-contracts.send-work-contract')
            ->with([
                'workContract' => $this->workContract,
            ]);
    }
}
