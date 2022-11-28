<?php

namespace App\Http\Requests\Company\WarrantyWorks;

use App\Models\{Warranty\Warranty, Work\Work};
use Illuminate\Foundation\Http\FormRequest;

class AttachWarrantyWorkRequest extends FormRequest
{
    /**
     * Warranty model class container
     *
     * @var Warranty|null
     */
    private $warranty;

    /**
     * Work model class container
     *
     * @var Work|null
     */
    private $work;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $warranty = $this->getWarranty();
        $work = $this->getWork();

        return $this->user()
            ->fresh()
            ->can('attach-warranty-work', [$warranty, $work]);
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
        $id = $this->input('warranty_id');

        return $this->warranty = Warranty::findOrFail($id);
    }

    /**
     * Get Work based on supplied input
     *
     * @return Work
     */
    public function getWork()
    {
        if ($this->work) {
            return $this->work;
        }
        $id = $this->input('work_id');

        return $this->work = Work::findOrFail($id);
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
