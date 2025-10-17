<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PurchaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'payment' => 'required',
            'address' => 'required',
        ];
    }

    public function messages(): array
    {
        return [
            'payment.required' => '支払い方法を選択してください。',
            'address.required' => '配送先を選択してください。',
        ];
    }
}
