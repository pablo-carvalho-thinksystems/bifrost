<?php

namespace App\Rules;

use App\Enums\TravelRequestStatusEnum;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class StatusChange implements ValidationRule
{

    private ?TravelRequestStatusEnum $currentStatus;

    public function __construct(string $currentStatus)
    {
        $this->currentStatus = TravelRequestStatusEnum::tryFrom($currentStatus);
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
    }
}
