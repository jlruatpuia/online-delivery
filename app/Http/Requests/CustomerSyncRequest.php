<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerSyncRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customers' => 'required|array',
            'customers.*.local_id' => 'required|integer',
            'customers.*.name' => 'required|string',
            'customers.*.phone_no' => 'nullable|string',
            'customers.*.address' => 'nullable|string',
            'customers.*.map_location' => 'nullable|array',
        ];
    }
}
