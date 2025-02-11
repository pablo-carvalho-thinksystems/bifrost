<?php

namespace Tests\Feature;

use App\Dtos\TravelRequestDto;
use App\Enums\TravelRequestStatusEnum;
use App\Events\NewTravelRequestStatusChangeEvent;
use App\Exceptions\ChangeStatusPermissionException;
use App\Services\TravelRequestService;
use App\Services\TravelRequestStatusService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;
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

        $user = $this->getManagerUser();
        $travelRequestDto = new TravelRequestDto();
        $travelRequestDto->fillFromArray($this->getPayload($user->id));
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

        $user = $this->getManagerUser();
        $travelRequestDto     = new TravelRequestDto();
        $travelRequestDto->fillFromArray($this->getPayload($user->id));
        auth()->login($user);

        $travelRequest = $travelRequestService->createTravelRequest($travelRequestDto);
        $this->assertEquals($travelRequest->status, TravelRequestStatusEnum::REQUESTED);

        $travelRequestStatusService = $this->getTravelRequestStatusServiceInstance();
        Event::fake();
        $travelRequestStatusService->updateTravelRequestStatus($travelRequest->id, TravelRequestStatusEnum::APPROVED);
        $travelRequest->refresh();
        Event::assertDispatched(NewTravelRequestStatusChangeEvent::class, function ($event) use ($user, $travelRequest) {
            return $event->user->id === $user->id &&
                $event->travelRequest->id === $travelRequest->id;
        });

        $this->assertEquals($travelRequest->status, TravelRequestStatusEnum::APPROVED->value);
    }

    public function testShouldThrowExceotiinOnApproveTravelRequestWithoutPermissions()
    {
        $this->seed();
        $travelRequestService = $this->getTravelRequestServiceInstance();

        $user = $this->getCustomerUser();
        $travelRequestDto     = new TravelRequestDto();
        $travelRequestDto->fillFromArray($this->getPayload($user->id));
        auth()->login($user);

        $travelRequest = $travelRequestService->createTravelRequest($travelRequestDto);
        $this->assertEquals($travelRequest->status, TravelRequestStatusEnum::REQUESTED);

        $travelRequestStatusService = $this->getTravelRequestStatusServiceInstance();
        $this->expectException(ChangeStatusPermissionException::class);
        $travelRequestStatusService->updateTravelRequestStatus($travelRequest->id, TravelRequestStatusEnum::APPROVED);

        $this->assertEquals($travelRequest->status, TravelRequestStatusEnum::REQUESTED);
    }

    public function testShouldListAllWithManager()
    {
        $this->seed();
        $travelRequestService = $this->getTravelRequestServiceInstance();

        $user = $this->getManagerUser();
        $travelRequestDto     = new TravelRequestDto();
        $travelRequestDto->fillFromArray($this->getPayload($user->id));
        auth()->login($user);

        $travelRequestService->createTravelRequest($travelRequestDto);

        $travelRequests = $travelRequestService->list();
        $this->assertCount(1, $travelRequests);

        $travelRequestService->createTravelRequest($travelRequestDto);
        $travelRequests = $travelRequestService->list();
        $this->assertCount(2, $travelRequests);
    }

    public function testOnlyCustomerTravelRequests()
    {
        $this->seed();
        $travelRequestService = $this->getTravelRequestServiceInstance();

        $userCustomer = $this->getCustomerUser();
        $travelRequestDto     = new TravelRequestDto();
        $travelRequestDto->fillFromArray($this->getPayload($userCustomer->id));
        auth()->login($userCustomer);

        $travelRequestService->createTravelRequest($travelRequestDto);

        $travelRequests = $travelRequestService->list();
        $this->assertCount(1, $travelRequests);

        $travelRequestService->createTravelRequest($travelRequestDto);

        $travelRequests = $travelRequestService->list();
        $this->assertCount(2, $travelRequests);

        $userManager = $this->getManagerUser();
        $travelRequestDto     = new TravelRequestDto();
        $travelRequestDto->fillFromArray($this->getPayload($userManager->id));
        auth()->login($userManager);

        $travelRequestService->createTravelRequest($travelRequestDto);

        auth()->login($userCustomer);
        $travelRequests = $travelRequestService->list();
        $this->assertCount(2, $travelRequests);

        auth()->login($userManager);
        $travelRequests = $travelRequestService->list();
        $this->assertCount(3, $travelRequests);
    }

    public function testShouldShowCustomerTravelRequest()
    {
        $this->seed();
        $this->getManagerUser();

        $travelRequestService = $this->getTravelRequestServiceInstance();

        $userCustomer = $this->getCustomerUser();
        $travelRequestDto = new TravelRequestDto();
        $travelRequestDto->fillFromArray($this->getPayload($userCustomer->id));
        auth()->login($userCustomer);

        $createdTravelRequest = $travelRequestService->createTravelRequest($travelRequestDto);
        $travelRequest = $travelRequestService->show($createdTravelRequest->external_id);
        $this->assertSame($createdTravelRequest->id, $travelRequest->id);

        $userManager = $this->getManagerUser();
        $travelRequestDto = new TravelRequestDto();
        $travelRequestDto->fillFromArray($this->getPayload($userManager->id));
        auth()->login($userManager);

        $createdTravelRequest = $travelRequestService->createTravelRequest($travelRequestDto);

        auth()->login($userCustomer);
        $travelRequest = $travelRequestService->show($createdTravelRequest->external_id);
        $this->assertNull($travelRequest);
    }

    private function getTravelRequestServiceInstance(): TravelRequestService
    {
        return app(TravelRequestService::class);
    }

    private function getTravelRequestStatusServiceInstance(): TravelRequestStatusService
    {
        return app(TravelRequestStatusService::class);
    }

    private function getPayload(int $userId): array
    {
        return [
            'user_id'        => $userId,
            'departure_date' => '2022-01-01',
            'return_date'    => '2022-01-10',
            'destination'    => 'Paris',
            'status'         => TravelRequestStatusEnum::REQUESTED->value
        ];
    }
}
