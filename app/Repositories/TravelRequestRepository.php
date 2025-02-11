<?php

namespace App\Repositories;

use App\Dtos\TravelRequestDto;
use App\Enums\TravelRequestStatusEnum;
use App\Models\TravelRequest;
use App\Repositories\Abstracts\AbstractBaseModel;
use App\Repositories\Contracts\TravelRequestRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;

class TravelRequestRepository extends AbstractBaseModel implements TravelRequestRepositoryInterface
{
    protected $model = TravelRequest::class;

    public function create(TravelRequestDto $travelRequestDto): ?TravelRequest
    {
        $travelRequestDto->created_at = Carbon::now();
        $travelRequestDto->updated_at = Carbon::now();
        $travelRequestDto->status = TravelRequestStatusEnum::REQUESTED;
        return $this->getModel()->query()->create([
            'external_id'    => $travelRequestDto->external_id ?? '',
            'user_id'        => $travelRequestDto->user_id,
            'status'         => $travelRequestDto->status,
            'destination'    => $travelRequestDto->destination,
            'departure_date' => $travelRequestDto->departure_date,
            'return_date'    => $travelRequestDto->return_date,
        ]);
    }

    public function list(array $filters = []): LengthAwarePaginator
    {
        return $this->getModel()
            ->query()
            ->visibilityScope()
            ->when($filters['status'] ?? null, fn($query, $status) => $query->where('status', $status))
            ->when($filters['partida_inicio'] ?? null,
                fn($query, $partidaInicio) => $query->where('departure_date', '>=', $partidaInicio))
            ->when($filters['partida_fim'] ?? null,
                fn($query, $partidaFim) => $query->where('departure_date', '<=', $partidaFim))
            ->when($filters['destination'] ?? null, fn($query, $destino) => $query->where('destination', $destino))
            ->paginate();
    }

    public function show(string $id): ?TravelRequest
    {
        return $this->getModel()
            ->query()
            ->visibilityScope()
            ->where('external_id', $id)
            ->first();
    }

    public function canBeCancelled(int $travelRequestId): bool
    {
        $travelRequest = $this->getModel()
            ->query()
            ->where('id', $travelRequestId)
            ->first();

        if (is_null($travelRequest)) {
            return false;
        }

        return Carbon::now()->diffInDays(Carbon::parse($travelRequest->departure_date),
                false) >= config('bifrost.DAYS_FOR_CANCEL');
    }
}