<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'user_id' => 'required|integer',
            'restaurant_id' => 'required|integer',
            'datetime' => 'required|date_format:Y-m-d H:i|after_or_equal:now',
            'number' => 'required|integer|max:20',
        ];
    }
}
