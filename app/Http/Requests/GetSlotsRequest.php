<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GetSlotsRequest extends BaseApiRequest
{
    public function authorize(): bool
    {
        return true; // can apply auth logic here
    }

    public function rules(): array
    {
        return [
            'pitch_id' => 'required|exists:pitches,id',
            'date' => 'required|date',
            'duration' => 'required|in:60,90',
        ];
    }
}
