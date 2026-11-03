<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SavingsGoalRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:100'],
            'description' => ['nullable', 'string', 'max:1000'],
            'target_amount' => ['required', 'numeric', 'min:1000', 'max:9999999999999'],
            'deadline' => ['nullable', 'date', 'after:today'],
            'color' => ['required', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'status' => ['sometimes', Rule::in(['active', 'completed', 'cancelled'])],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nama target wajib diisi.',
            'target_amount.required' => 'Nominal target wajib diisi.',
            'target_amount.min' => 'Target minimal Rp1.000.',
            'deadline.after' => 'Tenggat harus setelah hari ini.',
            'color.regex' => 'Warna harus dalam format hex.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('target_amount')) {
            $this->merge([
                'target_amount' => (float) preg_replace('/[^0-9]/', '', (string) $this->input('target_amount')),
            ]);
        }
    }
}
