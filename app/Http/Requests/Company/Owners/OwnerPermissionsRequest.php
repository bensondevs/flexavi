<?php

namespace App\Http\Requests\Company\Owners;

use App\Models\Owner\Owner;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class OwnerPermissionsRequest extends FormRequest
{
    /**
     * Owner instance container property.
     *
     * @var Owner|null
     */
    private ?Owner $owner = null;

    /**
     * Get owner instance that the permissions will be populated.
     *
     * @return Builder|Collection|Model|Builder[]
     */
    public function getOwner(): Builder|array|Collection|Model
    {
        if ($this->owner instanceof Owner) {
            return $this->owner;
        }

        $ownerId = $this->input('owner_id');
        return $this->owner = Owner::with('user')
            ->findOrFail($ownerId);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()
            ->fresh()
            ->can('view-owner', $this->getOwner());
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'owner_id' => ['required', 'string', 'exists:owners,id'],
        ];
    }
}
