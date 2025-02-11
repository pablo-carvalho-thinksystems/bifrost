<?php

namespace App\Repositories\Contracts;

use App\Dtos\TravelRequestDto;
use App\Models\TravelRequest;

interface TravelRequestRepositoryInterface
{
    public function create(TravelRequestDto $travelRequestDto): ?TravelRequest;
}