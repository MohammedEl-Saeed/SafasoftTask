<?php
namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ListSlotsRequest extends BaseApiRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'date' => 'required|date_format:Y-m-d',
            'duration' => 'required|in:60,90',
        ];
    }
}
