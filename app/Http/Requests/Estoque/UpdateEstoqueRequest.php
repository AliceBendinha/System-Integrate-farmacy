<?php

namespace App\Http\Requests\Estoque;

use Illuminate\Foundation\Http\FormRequest;

class UpdateEstoqueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'quantidade' => 'sometimes|integer|min:0',
            'stock_minimo' => 'sometimes|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'quantidade.integer' => 'A quantidade deve ser um número inteiro',
            'stock_minimo.integer' => 'O estoque mínimo deve ser um número inteiro',
        ];
    }
}
