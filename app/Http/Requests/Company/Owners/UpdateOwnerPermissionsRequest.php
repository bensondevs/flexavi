<?php

namespace App\Http\Requests\Company\Owners;

use App\Models\Owner\Owner;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOwnerPermissionsRequest extends FormRequest
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
        return $this->owner = Owner::findOrFail($ownerId);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $user = $this->user()->fresh();
        $owner = $this->getOwner();

        if ($owner->isMainOwner()) {
            abort(403, 'Cannot edit permission to main owner.');
        }

        return $user->can('edit-owner', $owner);
    }

    /**
     * Handle permissions array input.
     *
     * @return void
     */
    protected function prepareForValidation(): void
    {
        if (is_array($this->input('permission_names'))) {
            return;
        }

        $permissionsArray = json_decode(
            $this->input('permission_names'),
            true
        );
        if ($permissionsArray) {
            $this->merge([
                'permission_names' => $permissionsArray,
            ]);
        }
    }

    /**
     * Get permissions submitted to the endpoint.
     *
     * @return array
     */
    public function permissions(): array
    {
        $permissions = $this->input('permission_names');
        return is_array($permissions) ?
            array_filter($permissions) : [$permissions];
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
            'permission_names' => ['required'], // Can be string or array
        ];
    }
}
