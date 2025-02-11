<?php

namespace App\Enums;

enum TravelRequestStatusEnum: string
{
    case REQUESTED = 'requested';
    case APPROVED = 'approved';
    case CANCELLED = 'cancelled';


    public function canSwitchFor(TravelRequestStatusEnum $requestStatusEnum): bool
    {
        return in_array($requestStatusEnum, $this->availableStatusChanges());
    }

    public function availableStatusChanges(): array
    {
        return match ($this) {
            self::REQUESTED => [
                self::APPROVED,
                self::CANCELLED,
            ],
            self::APPROVED => [
                self::CANCELLED
            ],
            self::CANCELLED => [],
        };
    }

    public function getRequestedPermissions(): array
    {
        return match ($this) {
            self::REQUESTED => [],
            self::APPROVED => [
                AvailablePermissionsEnum::APPROVE_ORDER->value
            ],
            self::CANCELLED => [
                AvailablePermissionsEnum::CANCEL_ORDER->value
            ],
        };
    }
}
