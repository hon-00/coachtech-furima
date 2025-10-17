<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png|max:1024',
            'category_id' => 'required|array|min:1|max:3',
            'category_id.*' => 'exists:categories,id',
            'condition' => 'required|string|max:50',
            'brand' => 'nullable|string|max:100',
            'price' => 'required|numeric|min:0|max:1000000',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => '商品名を入力してください。',
            'description.required' => '商品説明を入力してください。',
            'image.required' => '商品画像を入力してください。',
            'image.image' => '画像ファイルである必要があります。',
            'image.mimes' => 'JPEGまたはPNG形式の画像を選択してください。',
            'category_id.required' => 'カテゴリーを入力してください。',
            'category_id.*.exists' => '選択されたカテゴリーは存在しません。',
            'category_id.max' => 'カテゴリーは最大3つまで選択できます。',
            'condition.required' => '商品の状態を入力してください。',
            'price.required' => '価格を入力してください。',
            'price.numeric' => '価格は数値で入力してください。',
            'price.min' => '価格は0円以上にしてください。',
        ];
    }
}
