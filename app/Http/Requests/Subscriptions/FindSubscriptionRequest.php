<?php

namespace App\Http\Requests\Subscriptions;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Gate;

use App\Traits\RequestHasRelations;
use App\Models\Subscription;

class FindSubscriptionRequest extends FormRequest
{
    use RequestHasRelations;

    /**
     * List of configirable relationships
     * 
     * @var array
     */
    protected $relationNames = [
        'with_company' => false,
        'with_plan' => true,
        'with_payment' => true,
    ];

    /**
     * Found subscription container
     * 
     * @var \App\Models\Subscription|null 
     */
    private $subscription;

    /**
     * Get the subscription by supplied input of
     * "id" or "subscription_id", if not found abort 404
     * 
     * @return \App\Models\Subscription|abort 404
     */
    public function getSubscription()
    {
        if ($this->subscription) return $this->subscription;

        $relations = $this->relations();

        $id = $this->input('subscription_id') ?: $this->input('id');
        return $this->subscription = Subscription::with($relations)
            ->findOrFail($id);
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $subscription = $this->getSubscription();
        return Gate::allows('view-subscription', $subscription);
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
