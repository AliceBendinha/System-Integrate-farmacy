<?php

namespace App\Http\Requests\Estoque;

use Illuminate\Foundation\Http\FormRequest;

class StoreEstoqueRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'farmacia_id' => 'required|exists:farmacias,id',
            'produto_id' => 'required|exists:produtos,id|unique:estoques,produto_id,NULL,id,farmacia_id,' . $this->farmacia_id,
            'quantidade' => 'required|integer|min:0',
            'stock_minimo' => 'required|integer|min:0',
        ];
    }

    public function messages(): array
    {
        return [
            'farmacia_id.required' => 'A farmácia é obrigatória',
            'farmacia_id.exists' => 'Farmácia inválida',
            'produto_id.required' => 'O produto é obrigatório',
            'produto_id.exists' => 'Produto inválido',
            'produto_id.unique' => 'Este produto já existe nesta farmácia',
            'quantidade.required' => 'A quantidade é obrigatória',
            'quantidade.integer' => 'A quantidade deve ser um número inteiro',
            'stock_minimo.required' => 'O estoque mínimo é obrigatório',
        ];
    }
}
