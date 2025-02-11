<?php

namespace App\Services;

use App\Enums\TravelRequestStatusEnum;
use App\Events\NewTravelRequestStatusChangeEvent;
use App\Exceptions\ChangeStatusPermissionException;
use App\Models\TravelRequest;
use App\Models\TravelRequestStatusHistory;
use App\Repositories\Contracts\TravelRequestStatusRepositoryInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Throwable;

class TravelRequestStatusService
{
    public function __construct(private TravelRequestStatusRepositoryInterface $travelRequestStatusRepository) {}

    public function updateTravelRequestStatus(int $travelRequestId, TravelRequestStatusEnum $newStatus): ?TravelRequest
    {
        $user = auth()->user();

        if (!$user->can($newStatus->getRequestedPermissions())) {
            throw new ChangeStatusPermissionException();
        }

        $travelRequest = null;

        try {
            DB::beginTransaction();
            $travelRequest = $this->travelRequestStatusRepository->updateTravelRequestStatus($travelRequestId, $newStatus);
            $this->addStatusChangeHistory($travelRequest->id, $newStatus);
            NewTravelRequestStatusChangeEvent::dispatch($user, $travelRequest);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            dd($throwable->getMessage());
        }

        return $travelRequest;
    }

    public function addStatusChangeHistory(int $travelRequestId, TravelRequestStatusEnum $newStatus): ?TravelRequestStatusHistory
    {
        return $this->travelRequestStatusRepository->addStatusChangeHistory($travelRequestId, $newStatus);
    }
}
