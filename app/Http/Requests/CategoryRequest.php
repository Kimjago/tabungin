<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;
        $categoryId = $this->route('category')?->id;

        return [
            'name' => [
                'required', 'string', 'max:60',
                Rule::unique('categories', 'name')
                    ->where('user_id', $userId)
                    ->ignore($categoryId),
            ],
            'type' => ['required', Rule::in(['income', 'expense'])],
            'color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'icon' => ['required', 'string', 'max:40'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama kategori wajib diisi.',
            'name.unique' => 'Kamu sudah punya kategori dengan nama ini.',
            'type.required' => 'Jenis kategori wajib dipilih.',
            'color.regex' => 'Warna harus dalam format hex, contoh #6366f1.',
        ];
    }
}
