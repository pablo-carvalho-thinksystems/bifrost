<?php

namespace App\Http\Controllers;

use App\Enums\TravelRequestStatusEnum;
use App\Http\Requests\StatusChangeRequest;
use App\Services\TravelRequestStatusService;

class TravelRequestStatusController extends Controller
{
    public function __construct(private TravelRequestStatusService $travelRequestStatusService) {}

    public function changeStatus(StatusChangeRequest $request)
    {
        $this->travelRequestStatusService->updateTravelRequestStatus(
            $request->input('travel_request_id'),
            TravelRequestStatusEnum::tryFrom($request->input('new_status'))
        );

        return response()->json(['message' => 'Status changed successfully']);
    }
}
