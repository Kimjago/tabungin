<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TransactionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $userId = $this->user()->id;

        return [
            'type' => ['required', Rule::in(['income', 'expense'])],
            'amount' => ['required', 'numeric', 'min:1', 'max:9999999999999'],
            'category_id' => [
                'nullable',
                Rule::exists('categories', 'id')->where('user_id', $userId),
            ],
            'note' => ['nullable', 'string', 'max:255'],
            'date' => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    public function messages(): array
    {
        return [
            'type.required' => 'Jenis transaksi wajib dipilih.',
            'type.in' => 'Jenis transaksi tidak valid.',
            'amount.required' => 'Nominal wajib diisi.',
            'amount.numeric' => 'Nominal harus berupa angka.',
            'amount.min' => 'Nominal minimal Rp1.',
            'category_id.exists' => 'Kategori tidak ditemukan.',
            'note.max' => 'Catatan maksimal 255 karakter.',
            'date.required' => 'Tanggal wajib diisi.',
            'date.date' => 'Format tanggal tidak valid.',
            'date.before_or_equal' => 'Tanggal tidak boleh di masa depan.',
        ];
    }

    protected function prepareForValidation(): void
    {
        if ($this->filled('amount')) {
            // terima "1.500.000" maupun "1500000"
            $this->merge([
                'amount' => (float) preg_replace('/[^0-9]/', '', (string) $this->input('amount')),
            ]);
        }
    }
}
