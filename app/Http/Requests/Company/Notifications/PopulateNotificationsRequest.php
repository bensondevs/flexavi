<?php

namespace App\Http\Requests\Company\Notifications;

use App\Enums\Notification\NotificationPopulateType;
use App\Enums\Notification\NotificationType;
use App\Traits\CompanyPopulateRequestOptions;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PopulateNotificationsRequest extends FormRequest
{
    use CompanyPopulateRequestOptions;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()
            ->fresh()
            ->can('view-notification');
    }

     /**
     * Get universal options
     *
     * @return array
     */
    public function options(): array
    {
        $this->addWith('actor');

        if ($keyword = $this->get('keyword', $this->get('search', null))) {
            $this->setSearch($keyword);
            $this->setSearchScope('table_scope_only');
        }

        switch (true) {
            case strtobool($this->has('read')):
                $this->addWhere([
                    'column' => 'read_at',
                    'operator' => "!=",
                    'value' => null
                ]);
                break;
            case strtobool($this->has('unread')):
                $this->addWhere([
                    'column' => 'read_at',
                    'value' => null
                ]);
                break;
        }

        if ($type = $this->get('type')) {
            $this->addWhere([
                'column' => 'type',
                'operator' => '=',
                'value' => $type,
            ]);
        }
        if ($types = $this->get('types')) {
            $types = explode(',', $types);
            $this->addWhereIn([
                'column' => 'type',
                'values' => $types,
            ]);
        }

        if ($time = $this->get('time')) {
            $createdAt = now();
            switch ($time) {
                case NotificationPopulateType::Today:
                    $createdAt->startOfDay();
                    break;
                case NotificationPopulateType::Last3Days:
                    $createdAt->subDays(3);
                    break;
                case NotificationPopulateType::Last7Days:
                    $createdAt->subDays(7);
                    break;
                case NotificationPopulateType::Last30Days:
                    $createdAt->subDays(30);
                    break;
                case NotificationPopulateType::ThisYear:
                    $createdAt->startOfYear();
                    break;
            }

            $this->addWhere([
                'column' => 'created_at',
                'operator' => '>=',
                'value' => $createdAt->toDateTimeString(),
            ]);
        }

        $this->addOrderBy('created_at', 'DESC');

        return $this->collectOptions();
    }


    /**
     * Get company options
     *
     * @return array
     */
    public function companyOptions(): array
    {
        $this->options();
        return $this->collectCompanyOptions();
    }

    /**
     * Get notifier options
     *
     * @return array
     */
    public function notifierOptions(): array
    {
        $user = $this->user();

        $this->addWhere([
            'column' => 'notifier_type',
            'value' => get_class($user),
        ]);

        $this->addWhere([
            'column' => 'notifier_id',
            'value' => $user->id,
        ]);

        $this->options();

        return $this->collectOptions();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'type' => ['nullable', Rule::in(NotificationType::getValues()) ],
            'types' => ['nullable', 'string'],
            'time' => ['nullable', Rule::in(NotificationPopulateType::getValues()) ],
        ];
    }
}
