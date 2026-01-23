<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $transaction = $this->route('transaction');
        $message = $this->route('message');
        $user = $this->user();

        if (! $transaction || ! $message || ! $user) {
            return false;
        }

        return (int) $message->transaction_id === (int) $transaction->id
            && (
                (int) $transaction->buyer_id === (int) $user->id
                || (int) $transaction->seller_id === (int) $user->id
        )
        && (int) $message->sender_id === (int) $user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'edit_body' => 'required|string|max:400',
        ];
    }

    public function messages(): array
    {
        return [
            'edit_body.required' => '本文を入力してください',
            'edit_body.max'      => '本文は400文字以内で入力してください',
        ];
    }
}
