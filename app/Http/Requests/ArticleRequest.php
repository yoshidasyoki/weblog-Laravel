<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ArticleRequest extends FormRequest
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
            'title' => ['required', 'max:50'],
            'body' => ['max:1000'],
            'is_public' => ['required', 'boolean'],
        ];
    }

    public function messages()
    {
        return [
            'title.required' => 'タイトルは必須です',
            'title.max' => '50文字以内で入力してください',
            'body.max' => '1000文字以内で入力してください',
            'is_public.required' => '公開設定は必須です',
            'is_public.boolean' => '入力に誤りがあります'
        ];
    }
}
