<?php

namespace App\Http\Requests\Receipts;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\InputRequest;

use App\Rules\Base64Image;
use App\Rules\Base64MaxSize;

use App\Models\Receipt;
use App\Models\Workday;
use App\Models\Worklist;
use App\Models\Appointment;

class SaveReceiptRequest extends FormRequest
{
    use InputRequest;

    private $receipt;

    private $workday;
    private $worklist;
    private $appointment;
    private $receiptable;

    public function getReceipt()
    {
        if ($this->receipt) return $this->receipt;

        $id = $this->input('id') ?: $this->input('receipt_id');
        $receipt = Receipt::with('receiptable')->findOrFail($id);
        $this->receiptable = $receipt->receiptable;

        return $receipt;
    }

    public function getReceiptable()
    {
        if ($this->receiptable) return $this->receiptable;

        if ($this->input('workday_id')) {
            return $this->receiptable = $this->getWorkday();
        }

        if ($this->input('worklist_id')) {
            return $this->receiptable = $this->getWorklist();
        }

        if ($this->input('appointment_id')) {
            return $this->receiptable = $this->getAppointment();
        }

        return null;
    }

    public function getWorkday()
    {
        if ($this->workday) return $this->workday;

        $id = $this->input('workday_id');
        return $this->workday = Workday::findOrFail($id);
    }

    public function getWorklist()
    {
        if ($this->worklist) return $this->worklist;

        $id = $this->input('worklist_id');
        return $this->worklist = Worklist::findOrFail($id);
    }

    public function getAppointment()
    {
        if ($this->appointment) return $this->appointment;

        $id = $this->input('appointment_id');
        return $this->appointment = Appointment::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $receiptable = $this->getReceiptable();

        if (! $this->isMethod('POST')) {
            $receipt = $this->getReceipt();
            return Gate::allows('edit-receipt', [$receipt, $receiptable]);
        }

        return Gate::allows('create-receipt', $receiptable);
    }

    protected function prepareForValidation()
    {
        $this->replace(array_filter($this->all()));

        if ($this->input('id') || $this->input('receipt_id')) {
            $this->getReceipt();
        }

        if ($receiptable = $this->getReceiptable()) {
            $this->merge([
                'company_id' => $receiptable->company_id,
                'receiptable_id' => $receiptable->id,
                'receiptable_type' => get_class($receiptable),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setRules([
            'company_id' => ['required', 'string'],

            'receiptable_type' => ['required', 'string'],
            'receiptable_id' => ['required', 'string'],

            'receipt_image' => ['file', 'max:5126', 'mimes:png,jpg,jpeg,svg'],
            
            'description' => ['string'],
        ]);

        if (is_base64_string($this->input('receipt_image'))) {
            $this->addRule('receipt_image', [new Base64Image, new Base64MaxSize(5126000)]);
        }

        return $this->returnRules();
    }
}
