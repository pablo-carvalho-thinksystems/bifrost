<?php

namespace App\Rules;

use App\Enums\TravelRequestStatusEnum;
use App\Services\TravelRequestService;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StatusChange implements ValidationRule
{

    private ?TravelRequestStatusEnum $currentStatus;
    private string $travelRequestId;

    public function __construct(string $currentStatus, string $travelRequestId)
    {
        $this->currentStatus   = TravelRequestStatusEnum::tryFrom($currentStatus);
        $this->travelRequestId = $travelRequestId;
    }

    /**
     * Run the validation rule.
     *
     * @param \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail, array $parameters = []): void
    {
        $newStatus = TravelRequestStatusEnum::tryFrom($value);

        if (is_null($newStatus) || is_null($this->currentStatus)) {
            return;
        }

        $requestedPermissions = $newStatus->getRequestedPermissions();

        $user = auth()->user();

        foreach ($requestedPermissions as $requestedPermission) {
            if (!$user->can($requestedPermission)) {
                $fail("Você não tem permissão para alterar o status da solicitação.");
                return;
            }
        }

        if (!$this->currentStatus->canSwitchFor($newStatus)) {
            $fail("O status '{$this->currentStatus->value}' não pode ser alterado para '$newStatus->value'.");
        }

        /**
         * @var TravelRequestService $travelRequestService
         */
        $travelRequestService = app(TravelRequestService::class);

        $daysForCancel = config('bifrost.DAYS_FOR_CANCEL');

        if (($this->currentStatus == TravelRequestStatusEnum::APPROVED) && ($newStatus == TravelRequestStatusEnum::CANCELLED)) {
            if (!$travelRequestService->canBeCancelled($this->travelRequestId)) {
                $fail("A solicitação só pode ser cancelada no máximo em até {$daysForCancel} dias antes da partida");
            }
        }
    }
}
