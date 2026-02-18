<?php

namespace App\Http\Requests\Servico;

use Illuminate\Foundation\Http\FormRequest;

class UpdateServicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'sometimes|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'preco' => 'sometimes|numeric|min:0.01',
        ];
    }

    public function messages(): array
    {
        return [
            'preco.numeric' => 'O preço deve ser um número',
        ];
    }
}
