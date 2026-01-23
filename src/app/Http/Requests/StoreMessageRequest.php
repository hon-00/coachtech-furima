<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMessageRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $transaction = $this->route('transaction');
        $user = $this->user();

        if (! $transaction || ! $user) {
            return false;
        }

        return $transaction->buyer_id === $user->id
            || $transaction->seller_id === $user->id;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'body' => 'required|string|max:400',
            'image' => 'nullable|file|mimes:jpeg,png',
        ];
    }

    public function messages(): array
    {
        return [
            'body.required' => '本文を入力してください',
            'body.max'      => '本文は400文字以内で入力してください',
            'image.mimes'   => '「.png」または「.jpeg」形式でアップロードしてください',
        ];
    }

    protected function failedValidation(Validator $validator): void
    {
        $userId = optional($this->user())->id;
        $transactionId = $this->route('transaction')?->id;

        if ($userId && $transactionId) {
            session()->put("message_drafts.{$userId}.{$transactionId}", (string) $this->input('body'));
        }

        throw new HttpResponseException(
            redirect()->back()
                ->withErrors($validator)
                ->withInput()
        );
    }
}
