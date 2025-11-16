<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProfileRequest extends FormRequest
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
            'avatar' => ['nullable', 'file', 'mimes:jpeg,png', 'max:2048'], 

            'name' => ['required', 'string', 'max:20'],

            'postal_code' => ['required', 'regex:/^\d{3}-\d{4}$/', 'max:8'],

            'address' => ['required'],
        ];
    }

    public function messages()
    {
        return [
            'avatar.mimes' => 'プロフィール画像はjpegまたはpng形式でアップロードしてください。',
            'avatar.max' => 'プロフィール画像のサイズは2MB以内にしてください。',

            'name.required' => 'ユーザー名を入力してください。',
            'name.max' => 'ユーザー名は20文字以内で入力してください。',

            'postal_code.required' => '郵便番号を入力してください。',
            'postal_code.regex' => '郵便番号はハイフンありの8文字形式（例：123-4567）で入力してください。',

            'address.required' => '住所を入力してください。',
        ];
    }
}
