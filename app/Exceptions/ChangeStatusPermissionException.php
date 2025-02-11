<?php

namespace App\Exceptions;

use Exception;

class ChangeStatusPermissionException extends Exception
{
    protected $message = 'You do not have permission to change the status of this travel request.';
}
