<?php

namespace App\Services;

use App\Dtos\TravelRequestDto;
use App\Enums\TravelRequestStatusEnum;
use App\Models\TravelRequest;
use App\Repositories\Contracts\TravelRequestRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Throwable;

class TravelRequestService
{
    public function __construct
    (
        private TravelRequestRepositoryInterface $travelRequestRepository,
        private TravelRequestStatusService $travelRequestStatusService
    ) {}

    public function createTravelRequest(TravelRequestDto $travelRequestDto): ?TravelRequest
    {
        $travelRequest = null;

        try {
            DB::beginTransaction();
            $travelRequest = $this->travelRequestRepository->create($travelRequestDto);
            $this->travelRequestStatusService->addStatusChangeHistory($travelRequest->id, TravelRequestStatusEnum::REQUESTED);
            DB::commit();
        } catch (Throwable $throwable) {
            DB::rollBack();
            dd($throwable->getMessage());
        }

        return $travelRequest;
    }

    public function list(array $filters = []): LengthAwarePaginator
    {
        return $this->travelRequestRepository->list($filters);
    }

    public function show(string $id): ?TravelRequest
    {
        return $this->travelRequestRepository->show($id);
    }

    public function canBeCancelled(int $travelRequestId): bool
    {
        return $this->travelRequestRepository->canBeCancelled($travelRequestId);
    }
}
