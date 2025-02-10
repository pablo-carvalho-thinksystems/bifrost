<?php

namespace App\Enums;

enum AvailableRolesEnum: string
{
    case MANAGER = 'manager';
    case CUSTOMER = 'customer';

    public function getPermissions(): array
    {
        return match ($this) {
            self::MANAGER => [
                AvailablePermissionsEnum::APPROVE_ORDER,
                AvailablePermissionsEnum::CANCEL_ORDER
            ],
            self::CUSTOMER => [
                AvailablePermissionsEnum::CANCEL_ORDER,
            ],
        };
    }
}
