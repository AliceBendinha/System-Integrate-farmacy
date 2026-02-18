<?php

namespace App\Http\Requests\Farmacia;

use Illuminate\Foundation\Http\FormRequest;

class StoreFarmaciaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'localizacao' => 'required|string|max:255',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome da farmácia é obrigatório',
            'nome.string' => 'O nome deve ser uma string',
            'localizacao.required' => 'A localização é obrigatória',
        ];
    }
}
