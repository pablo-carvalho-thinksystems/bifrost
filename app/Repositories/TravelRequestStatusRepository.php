<?php

namespace App\Repositories;

use App\Enums\TravelRequestStatusEnum;
use App\Models\TravelRequest;
use App\Models\TravelRequestStatusHistory;
use App\Repositories\Abstracts\AbstractBaseModel;
use App\Repositories\Contracts\TravelRequestStatusRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Throwable;

class TravelRequestStatusRepository extends AbstractBaseModel implements TravelRequestStatusRepositoryInterface
{
    protected $model = TravelRequestStatusHistory::class;

    public function updateTravelRequestStatus(int $travelRequestId, TravelRequestStatusEnum $newStatus)
    {
        $travelRequest = TravelRequest::query()->find($travelRequestId);

        if (!is_null($travelRequest)) {
            $travelRequest->status = $newStatus;
            $travelRequest->save();
        }

        return $travelRequest;
    }

    public function addStatusChangeHistory(
        int $travelRequestId,
        TravelRequestStatusEnum $newStatus
    ): ?TravelRequestStatusHistory {
        return $this->getModel()->query()->create([
            'travel_request_id' => $travelRequestId,
            'status'            => $newStatus,
            'user_id'           => auth()->id()
        ]);
    }
}