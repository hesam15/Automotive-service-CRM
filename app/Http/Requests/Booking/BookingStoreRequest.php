<?php

namespace App\Http\Requests\Booking;

use App\Helpers\PersianConvertNumberHelper;
use Illuminate\Foundation\Http\FormRequest;

class BookingStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool {
        return true;
    }

    protected function prepareForValidation() {
        $date = (new PersianConvertNumberHelper($this->date))
        ->convertDateToEnglish();

        $this->merge([
            'date' => $date->value
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array {
        

        return [
            'date' => 'required|date_format:Y-m-d',
            'time_slot' => 'required',
            'car_id' => 'required|exists:cars,id',
            'customer_id' => 'required|exists:customers,id',
        ];
    }
}
