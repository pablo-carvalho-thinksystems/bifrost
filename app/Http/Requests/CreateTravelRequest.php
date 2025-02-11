<?php

namespace App\Http\Requests;

use App\Enums\AvailableRolesEnum;
use App\Enums\TravelRequestStatusEnum;
use App\Models\TravelRequest;
use App\Rules\StatusChange;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class CreateTravelRequest extends FormRequest
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
        $this->merge([
            'user_id' => auth()->user()->id,
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'departure_date' => ['required', 'date'],
            'return_date' => ['required', 'date', 'after:departure_date'],
            'destination' => ['required', 'string'],
        ];
    }
}
