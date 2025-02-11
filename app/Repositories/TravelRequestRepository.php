<?php

namespace App\Repositories;

use App\Dtos\TravelRequestDto;
use App\Enums\TravelRequestStatusEnum;
use App\Models\TravelRequest;
use App\Repositories\Abstracts\AbstractBaseModel;
use App\Repositories\Contracts\TravelRequestRepositoryInterface;

class TravelRequestRepository extends AbstractBaseModel implements TravelRequestRepositoryInterface
{
    protected $model = TravelRequest::class;

    public function create(TravelRequestDto $travelRequestDto): ?TravelRequest
    {
        $travelRequestDto->status = TravelRequestStatusEnum::REQUESTED;
        return $this->getModel()->query()->create($travelRequestDto->toArray());
    }

    public function updateTravelRequestStatus(int $travelRequestId, TravelRequestStatusEnum $requestStatusEnum): ?TravelRequest
    {
        $travelRequest = $this->getModel()->query()->find($travelRequestId);

        if ($travelRequest) {
            $travelRequest->status = $requestStatusEnum;
            $travelRequest->save();
        }

        return $travelRequest;
    }
}