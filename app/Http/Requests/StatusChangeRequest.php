<?php

namespace App\Http\Requests;

use App\Enums\AvailableRolesEnum;
use App\Enums\TravelRequestStatusEnum;
use App\Models\TravelRequest;
use App\Rules\StatusChange;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StatusChangeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation(): void
    {
        $travelRequest = TravelRequest::query()->find($this->input('travel_request_id'));

        $this->merge([
            'current_status' => $travelRequest?->status
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $user = auth()->user();
        $travelRequestExistsRule = $user->hasRole(AvailableRolesEnum::MANAGER->value)
            ? 'exists:travel_requests,id'
            : 'exists:travel_requests,id,user_id,' . $user->id;

        return [
            'travel_request_id' => ['required', $travelRequestExistsRule],
            'current_status'    => ['required', 'string', new Enum(TravelRequestStatusEnum::class)],
            'new_status'        => [
                'required',
                'string',
                new Enum(TravelRequestStatusEnum::class),
                new StatusChange(request('current_status'), request('travel_request_id'))
            ],
        ];
    }
}
