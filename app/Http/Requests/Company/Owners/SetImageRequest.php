<?php

namespace App\Http\Requests\Company\Owners;

use App\Models\Owner\Owner;
use App\Rules\Helpers\Media;
use Illuminate\Foundation\Http\FormRequest;

class SetImageRequest extends FormRequest
{
    /**
     * Owner model container variable
     *
     * @var Owner|null
     */
    private ?Owner $owner = null;

    /**
     * Get Owner based on supplied input
     *
     * @return Owner|null
     */
    public function getOwner(): ?Owner
    {
        if ($this->owner) {
            return $this->owner;
        }

        $id = $this->input('owner_id');
        return $this->owner = Owner::findOrFail($id);
    }


    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'image' => [
                'required',
                'image',
                'max:' . Media::MAX_IMAGE_SIZE,
                'mimes:' . Media::imageExtensions(),
            ],
            'owner_id' => ['required', 'exists:owners,id'],
        ];
    }
}
