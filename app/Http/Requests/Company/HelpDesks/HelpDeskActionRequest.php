<?php

namespace App\Http\Requests\Company\HelpDesks;

use App\Models\HelpDesk\HelpDesk;
use App\Traits\CompanyInputRequest;
use App\Traits\RequestHasRelations;
use Illuminate\Foundation\Http\FormRequest;

class HelpDeskActionRequest extends FormRequest
{
    use CompanyInputRequest;
    use RequestHasRelations;

    /**
    * List of loadable relationships
    *
    * @var array
    */
    private $relationNames = [
        'with_user' => false,
    ];

    /**
    * Get HelpDesk based on supplied input
    *
    * @return HelpDesk
    */
    public function getHelpDesk()
    {
        if ($this->helpDesk) {
            return $this->helpDesk;
        }
        $id = $this->input('id') ?: $this->input('helpdesk_id');

        return $this->helpDesk =
            HelpDesk::with($this->getRelations())->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = $this->user()->fresh();

        switch (true) {
            case urlContains('/store'):
                return $user->can('create-help-desk');
            case urlContains('/view'):
                return $user->can('view-help-desk', $this->getHelpDesk());
            case urlContains('/update'):
                return $user->can('update-help-desk', $this->getHelpDesk());
            case urlContains('/delete'):
                return $user->can('delete-help-desk', $this->getHelpDesk());
            default:
                return false ;
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        switch (true) {
            case urlContains('/store'):
                return [
                    'title' => ['required' , 'string'],
                    'content' => ['required' , 'string'],
                    'user_id' => ['required', 'exists:users,id'],
                ];
            case urlContains('/update'):
                return [
                    'title' => ['nullable' , 'string'],
                    'content' => ['nullable' , 'string'],
                ];
            default:
                return [];
                break;
        }
    }

    /**
     * Prepare received request values before rules.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $user = $this->getCurrentUser();
        $this->merge(['user_id' => $user->id]);
    }
}
