<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DeliveryUploadRequest extends FormRequest
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
            'deliveries' => 'required|array',
            'deliveries.*.local_id' => 'required|integer',
            'deliveries.*.invoice_no' => 'required|string',
            'deliveries.*.customer_local_id' => 'required|integer',
            'deliveries.*.amount' => 'required|numeric',
            'deliveries.*.payment_type' => 'required|in:prepaid,cod',
            'deliveries.*.delivery_date' => 'nullable|date',
        ];
    }
}
