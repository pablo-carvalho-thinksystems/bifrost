<?php

namespace App\Enums;

enum AvailablePermissionsEnum: string
{
    case APPROVE_ORDER = 'approve_order';
    case CANCEL_ORDER = 'cancel_order';
}
