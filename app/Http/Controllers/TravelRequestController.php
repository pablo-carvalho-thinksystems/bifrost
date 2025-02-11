<?php

namespace App\Http\Controllers;

use App\Dtos\TravelRequestDto;
use App\Services\TravelRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TravelRequestController extends Controller
{
    public function __construct(private TravelRequestService $travelRequestService) {}

    public function create(Request $request): JsonResponse
    {
        $travelRequestDto = (new TravelRequestDto())->fillFromArray($request->all());
        $travelRequest = $this->travelRequestService->createTravelRequest($travelRequestDto);

        return response()->json($travelRequest, 201);
    }
}
