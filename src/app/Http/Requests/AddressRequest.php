<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AddressRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        // ログイン中のユーザーのみ許可
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/'], // 例: 123-4567
            'address'     => 'required|string|max:255',
            'building' => 'nullable|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex'    => '郵便番号はハイフンありで8文字にしてください。',
            'address.required'     => '住所を入力してください。',
            'address.max'          => '住所は255文字以内で入力してください。',
        ];
    }
}
