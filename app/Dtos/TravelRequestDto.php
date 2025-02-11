<?php

namespace App\Dtos;

use App\Dtos\Abstracts\ArrayableDto;
use App\Enums\TravelRequestStatusEnum;

class TravelRequestDto extends ArrayableDto
{
    public string $external_id;
    public int $user_id;
    public TravelRequestStatusEnum $status;
    public string $destination;
    public string $departure_date;
    public string $return_date;
    public string $created_at;
    public string $updated_at;
}