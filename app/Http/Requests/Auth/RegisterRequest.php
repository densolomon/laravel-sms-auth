<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'name' => 'required',
            'phone' => 'required'
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge(
            [
                'phone' => $this->cleanPhone($this->input('phone')),
            ]
        );
    }

    private function cleanPhone(string $phone): string
    {
        return preg_replace('/[^0-9]*/', '', $phone);
    }
}
