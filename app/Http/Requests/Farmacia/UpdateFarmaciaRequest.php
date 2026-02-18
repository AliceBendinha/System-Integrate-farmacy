<?php

namespace App\Http\Requests\Farmacia;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFarmaciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return auth('api')->user()?->is_admin || auth('api')->id() === $this->farmacia->user_id;
    }

    public function rules(): array
    {
        return [
            'nome' => 'sometimes|string|max:255',
            'localizacao' => 'sometimes|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.string' => 'O nome deve ser uma string',
        ];
    }
}
