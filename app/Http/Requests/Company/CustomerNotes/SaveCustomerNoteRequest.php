<?php

namespace App\Http\Requests\Company\CustomerNotes;

use App\Models\Customer\CustomerNote;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SaveCustomerNoteRequest extends FormRequest
{
    /**
     * Customer note instance container property
     *
     * @var CustomerNote|null
     */
    private ?CustomerNote $customerNote = null;

    /**
     * Get Customer NOte based on the supplied input
     *
     * @param bool $force
     * @return CustomerNote|null
     */
    public function getCustomerNote(bool $force = false): ?CustomerNote
    {
        if ($this->customerNote instanceof CustomerNote and not($force)) {
            return $this->customerNote;
        }

        $id = $this->input('id', $this->input('customer_note_id'));
        return $this->customerNote = CustomerNote::findOrFail($id);
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $user = $this->user()->fresh();
        return \Str::contains($this->url(), 'store') ? $user->can('create-customer-note') : $user->can('edit-customer-note');
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'string', Rule::exists('customers', 'id')],
            'note' => ['required', 'string'],
        ];
    }
}
