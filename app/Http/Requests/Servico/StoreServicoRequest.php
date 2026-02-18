<?php

namespace App\Http\Requests\Servico;

use Illuminate\Foundation\Http\FormRequest;

class StoreServicoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'farmacia_id' => 'required|exists:farmacias,id',
            'nome' => 'required|string|max:255',
            'descricao' => 'nullable|string|max:1000',
            'preco' => 'required|numeric|min:0.01',
        ];
    }

    public function messages(): array
    {
        return [
            'farmacia_id.required' => 'A farmácia é obrigatória',
            'farmacia_id.exists' => 'Farmácia inválida',
            'nome.required' => 'O nome do serviço é obrigatório',
            'preco.required' => 'O preço é obrigatório',
            'preco.numeric' => 'O preço deve ser um número',
        ];
    }
}
