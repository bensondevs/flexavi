<?php

namespace App\Http\Requests\Company\CarRegisterTimes;

use App\Models\Car\Car;
use App\Traits\{CompanyPopulateRequestOptions, RequestHasRelations};
use Illuminate\Foundation\Http\FormRequest;

class PopulateCarRegisterTimesRequest extends FormRequest
{
    use RequestHasRelations;
    use CompanyPopulateRequestOptions;

    /**
     * List configuration for relationship loaded
     *
     * @var array
     */
    private $relationNames = [
        'with_car' => false,
    ];

    /**
     * Car object
     *
     * @var Car|null
     */
    private $car;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $car = $this->getCar();

        return $this->user()
            ->fresh()
            ->can('view any car register times', $car);
    }

    /**
     * Get Car based on the supplied input
     *
     * @return Car
     */
    public function getCar()
    {
        if ($this->car) {
            return $this->car;
        }
        $id = $this->input('car_id');

        return $this->car = Car::findOrFail($id);
    }

    /**
     * Get populate query and settings
     *
     * @return array
     */
    public function options()
    {
        $this->addWhere([
            'column' => 'car_id',
            'operator' => '=',
            'value' => $this->getCar()->id,
        ]);
        if ($this->has('should_out_from') || $this->has('should_out_until')) {
            $shouldOutBetween = [];
            $from = lastWeek();
            $until = now();
            if ($this->has('should_out_from')) {
                if ($from = $this->input('should_out_from')) {
                    array_push($shouldOutBetween, $from);
                }
            }
            if ($this->has('should_out_until')) {
                if ($until = $this->input('should_out_until')) {
                    array_push($shouldOutBetween, $until);
                }
            }
            $this->addScope('shouldOutBetween', $shouldOutBetween);
        }
        if (
            $this->has('should_return_from') ||
            $this->has('should_return_until')
        ) {
            $shouldReturnBetween = [];
            $from = lastWeek();
            $until = now();
            if ($this->has('should_return_from')) {
                if ($from = $this->input('should_return_from')) {
                    array_push($shouldReturnBetween, $from);
                }
            }
            if ($this->has('should_return_until')) {
                if ($until = $this->input('should_return_until')) {
                    array_push($shouldReturnBetween, $until);
                }
            }
            $this->addScope('shouldReturnBetween', $shouldReturnBetween);
        }
        if ($this->has('marked_out_from') || $this->has('marked_out_until')) {
            $markedOutBetween = [];
            $from = lastWeek();
            $until = now();
            if ($this->has('marked_out_from')) {
                if ($from = $this->input('marked_out_from')) {
                    array_push($markedOutBetween, $from);
                }
            }
            if ($this->has('marked_out_until')) {
                if ($until = $this->input('marked_out_until')) {
                    array_push($markedOutBetween, $until);
                }
            }
            $this->addScope('markedOutBetween', $markedOutBetween);
        }

        return $this->collectOptions();
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
