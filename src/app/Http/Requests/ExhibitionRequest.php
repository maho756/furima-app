<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExhibitionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
     public function rules()
    {
        return [
            'image' => ['required', 'file', 'mimes:jpeg,png', 'max:2048'],

            'name' => ['required'],

            'brand' => ['nullable'],

            'description' => ['required', 'string', 'max:255'],

            'categories' => ['required', 'array'],

            'condition' => ['required'],

            'price' => ['required', 'numeric', 'min:0'],
        ];
    }

    public function messages()
    {
        return [
            'image.required' => '商品画像を選択してください。',
            'image.file' => '画像ファイルをアップロードしてください。',
            'image.mimes' => '画像はjpegまたはpng形式でアップロードしてください。',
            'image.max' => '画像サイズは2MB以内でアップロードしてください。',

            'name.required' => '商品名を入力してください。',

            'description.required' => '商品の説明を入力してください。',
            'description.max' => '商品の説明は255文字以内で入力してください。',

            'categories.required' => 'カテゴリーを選択してください。',
            'categories.array' => 'カテゴリーの形式が不正です。',

            'condition.required' => '商品の状態を選択してください。',

            'price.required' => '販売価格を入力してください。',
            'price.numeric' => '販売価格は数値で入力してください。',
            'price.min' => '販売価格は0円以上で入力してください。',
        ];
    }
}
