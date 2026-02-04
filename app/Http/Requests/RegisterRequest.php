<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'username' => ['required', 'max:50'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'min:8'],
        ];
    }

    public function messages()
    {
        return [
            'username.required' => 'ユーザー名は必須事項です',
            'username.max' => '50文字以内で入力してください',
            'email.required' => 'メールアドレスは必須事項です',
            'email.email' => '入力形式に誤りがあります',
            'email.unique' => 'このメールアドレスは既に使用されています',
            'password.required' => 'パスワードは必須事項です',
            'password.min' => '8文字以上で入力してください',
        ];
    }
}
