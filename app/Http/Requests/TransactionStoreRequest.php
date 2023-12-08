<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransactionStoreRequest extends FormRequest
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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'items.*.item' => 'required|exists:warehouse_items,id',
            'items.*.quantity' => 'required|numeric|gt:0',
            'account_id' => 'required|exists:accounts,id'
        ];
    }

    public function messages()
    {
        return [
            'items.*.item.exists' => 'One of the selected item does not exists. Refresh the browser and try again.',
            'items.*.item.required' => 'Please apply all fields in the items section',

            'items.*.quantity.numeric' => 'One of the selected quantity is not a number. Please try again',
            'items.*.quantity.required' => 'Please apply all fields in the quantity section',
            'items.*.quantity.gt' => 'All quantity must be greater than zero',
        ];
    }
}
