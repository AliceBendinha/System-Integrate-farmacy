<?php

namespace App\Http\Requests\Produto;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'sometimes|string|max:255',
            'codigo' => 'sometimes|string|unique:produtos,codigo,' . $this->produto->id . '|max:100',
            'preco' => 'sometimes|numeric|min:0.01',
            'data_validade' => 'nullable|date|after_or_equal:today',
            'categoria_id' => 'sometimes|exists:categorias,id',
        ];
    }

    public function messages(): array
    {
        return [
            'codigo.unique' => 'Este código já existe',
            'preco.numeric' => 'O preço deve ser um número',
            'categoria_id.exists' => 'Categoria inválida',
        ];
    }
}
