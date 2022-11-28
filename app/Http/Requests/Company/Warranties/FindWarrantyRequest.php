<?php

namespace App\Http\Requests\Company\Warranties;

use App\Models\Warranty\Warranty;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class FindWarrantyRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * Warranty model class container
     *
     * @var Warranty|null
     */
    private $warranty;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $warranty = $this->getWarranty();

        return $this->user()
            ->fresh()
            ->can('view-warranty', $warranty);
    }

    /**
     * Get Warranty based on supplied input
     *
     * @return Warranty
     */
    public function getWarranty()
    {
        if ($this->warranty) {
            return $this->warranty;
        }
        $id = $this->input('id');

        return $this->warranty = Warranty::with('warrantyAppointments')->findOrFail($id);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [];
    }
}
