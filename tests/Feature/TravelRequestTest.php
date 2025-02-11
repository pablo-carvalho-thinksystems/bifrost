<?php

namespace Tests\Feature;

use App\Dtos\TravelRequestDto;
use App\Enums\TravelRequestStatusEnum;
use App\Exceptions\ChangeStatusPermissionException;
use App\Models\User;
use App\Services\TravelRequestService;
use App\Services\TravelRequestStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TravelRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function testCreateTravelRequest(): void
    {
        $this->seed();
        $this->getManagerUser();

        $travelRequestService = $this->getTravelRequestServiceInstance();

        $travelRequestDto = new TravelRequestDto();
        $travelRequestDto->fillFromArray($this->getPayload());

        $user = $this->getManagerUser();
        auth()->login($user);

        $travelRequest = $travelRequestService->createTravelRequest($travelRequestDto);

        $this->assertDatabaseHas('travel_requests', [
            'id'             => $travelRequest->id,
            'user_id'        => $travelRequest->user_id,
            'departure_date' => $travelRequest->departure_date,
            'return_date'    => $travelRequest->return_date,
            'destination'    => $travelRequest->destination,
            'status'         => $travelRequest->status,
        ]);
    }

    public function testShouldApproveTravelRequestWithCorrectPermissions()
    {
        $this->seed();
        $travelRequestService = $this->getTravelRequestServiceInstance();
        $travelRequestDto     = new TravelRequestDto();
        $travelRequestDto->fillFromArray($this->getPayload());

        $user = $this->getManagerUser();
        auth()->login($user);

        $travelRequest = $travelRequestService->createTravelRequest($travelRequestDto);
        $this->assertEquals($travelRequest->status, TravelRequestStatusEnum::REQUESTED);

        $travelRequestStatusService = $this->getTravelRequestStatusServiceInstance();
        $travelRequestStatusService->updateTravelRequestStatus($travelRequest->id, TravelRequestStatusEnum::APPROVED);
        $travelRequest->refresh();

        $this->assertEquals($travelRequest->status, TravelRequestStatusEnum::APPROVED->value);
    }

    public function testShouldThrowExceotiinOnApproveTravelRequestWithoutPermissions()
    {
        $this->seed();
        $travelRequestService = $this->getTravelRequestServiceInstance();
        $travelRequestDto     = new TravelRequestDto();
        $travelRequestDto->fillFromArray($this->getPayload());

        $user = $this->getCustomerUser();
        auth()->login($user);

        $travelRequest = $travelRequestService->createTravelRequest($travelRequestDto);
        $this->assertEquals($travelRequest->status, TravelRequestStatusEnum::REQUESTED);

        $travelRequestStatusService = $this->getTravelRequestStatusServiceInstance();
        $this->expectException(ChangeStatusPermissionException::class);
        $travelRequestStatusService->updateTravelRequestStatus($travelRequest->id, TravelRequestStatusEnum::APPROVED);

        $this->assertEquals($travelRequest->status, TravelRequestStatusEnum::REQUESTED);
    }

    private function getTravelRequestServiceInstance(): TravelRequestService
    {
        return app(TravelRequestService::class);
    }

    private function getTravelRequestStatusServiceInstance(): TravelRequestStatusService
    {
        return app(TravelRequestStatusService::class);
    }

    private function getPayload(): array
    {
        return [
            'user_id'        => User::query()->first()->id,
            'departure_date' => '2022-01-01',
            'return_date'    => '2022-01-10',
            'destination'    => 'Paris',
            'status'         => TravelRequestStatusEnum::REQUESTED->value
        ];
    }
}
