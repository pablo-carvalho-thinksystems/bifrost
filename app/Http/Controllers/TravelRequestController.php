<?php

namespace App\Http\Controllers;

use App\Dtos\TravelRequestDto;
use App\Http\Requests\CreateTravelRequest;
use App\Http\Requests\TravelRequestShowRequest;
use App\Http\Resources\TravelRequestResource;
use App\Services\TravelRequestService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TravelRequestController extends Controller
{
    public function __construct(private TravelRequestService $travelRequestService)
    {
    }

    public function create(CreateTravelRequest $request): JsonResponse
    {
        $travelRequestDto = (new TravelRequestDto())->fillFromArray($request->all());
        $travelRequest    = $this->travelRequestService->createTravelRequest($travelRequestDto);

        return response()->json($travelRequest, 201);
    }

    public function list(Request $request): JsonResponse
    {
        $filters        = $request->only(['status', 'partida_inicio', 'partida_fim', 'destination']);
        $travelRequests = TravelRequestResource::collection($this->travelRequestService->list($filters));

        return response()->json($travelRequests, Response::HTTP_OK);
    }

    public function show(string $external_id): JsonResponse
    {
        $travelRequest = $this->travelRequestService->show($external_id);

        if (is_null($travelRequest)) {
            return response()->json([], Response::HTTP_NOT_FOUND);
        }

        return response()->json(TravelRequestResource::make($travelRequest), 200);
    }
}
