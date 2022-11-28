<?php

namespace App\Repositories\Inspection;

use App\Models\Inspection\Inspection;
use App\Models\Inspection\InspectionPicture;
use App\Models\WorkService\WorkService;
use App\Repositories\Base\BaseRepository;
use App\Repositories\Work\WorkRepository;
use Illuminate\Database\QueryException;

class InspectionPictureRepository extends BaseRepository
{


    private $workRepository;

    /**
     * Repository constructor method
     *
     * @return void
     */
    public function __construct(WorkRepository $workRepository)
    {
        $this->setInitModel(new InspectionPicture);
        $this->workRepository = $workRepository;
    }

    public function save(array $data, Inspection $inspection)
    {
        try {
            foreach ($data as $index => $row) {
                $inspectionPicture = $this->getModel();
                $inspectionPicture->fill([
                    'inspection_id' => $inspection->id,
                    'name' => 'Work on Roof ' . ($index + 1),
                    'length' => $row['length'],
                    'width' => $row['width'],
                    'amount' => $row['amount'],
                    'note' => isset($row['note']) ? $row['note'] : null,
                ]);
                $inspectionPicture->save();

                foreach ($row['pictures'] as $picture) $inspectionPicture->addMedia($picture)->toMediaCollection('inspection_pictures');

                foreach ($row['services'] as $service) {
                    $this->workRepository->save([
                        'work_service_id' => $service,
                        'company_id' => $inspection->company_id,
                        'quantity' => $row['amount'],
                        'unit_price' => WorkService::findOrFail($service)->price
                    ]);

                    $this->workRepository->attachTo($inspectionPicture);
                }

                $this->setModel($inspectionPicture);
                $this->destroyModel();
            }
            $this->setSuccess('Successfully create inspection picture.');
        } catch (QueryException $qe) {
            $error = $qe->getMessage();
            $this->setError('Failed to create inspection picture.', $error);
        }

        return $this->getModel();
    }
}
