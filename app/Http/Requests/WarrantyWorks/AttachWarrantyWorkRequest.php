<?php

namespace App\Http\Requests\WarrantyWorks;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;
use App\Models\{ Warranty, Work };

class AttachWarrantyWorkRequest extends FormRequest
{
    /**
     * Warranty model class container
     * 
     * @var \App\Models\Warranty|null
     */
    private $warranty;

    /**
     * Work model class container
     * 
     * @var \App\Models\Work|null
     */
    private $work;

    /**
     * Get warranty target
     * 
     * @return \App\Models\Warranty|abort 404
     */
    public function getWarranty()
    {
        if ($this->warranty) return $this->warranty;

        $id = $this->input('warranty_id');
        return $this->warranty = Warranty::findOrFail($id);
    }

    /**
     * Get work subject to be attached
     * 
     * @return \App\Models\Work|abort 404
     */
    public function getWork()
    {
        if ($this->work) return $this->work;

        $id = $this->input('work_id');
        return $this->work = Work::findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $warranty = $this->getWarranty();
        $work = $this->getWork();

        return Gate::allows('attach-warranty-work', [$warranty, $work]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            //
        ];
    }
}
