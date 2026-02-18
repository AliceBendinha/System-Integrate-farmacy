<?php

namespace App\Http\Requests\Produto;

use Illuminate\Foundation\Http\FormRequest;

class StoreProdutoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nome' => 'required|string|max:255',
            'codigo' => 'required|string|unique:produtos|max:100',
            'preco' => 'required|numeric|min:0.01',
            'data_validade' => 'nullable|date|after_or_equal:today',
            'categoria_id' => 'required|exists:categorias,id',
        ];
    }

    public function messages(): array
    {
        return [
            'nome.required' => 'O nome do produto é obrigatório',
            'codigo.required' => 'O código do produto é obrigatório',
            'codigo.unique' => 'Este código já existe',
            'preco.required' => 'O preço é obrigatório',
            'preco.numeric' => 'O preço deve ser um número',
            'categoria_id.required' => 'A categoria é obrigatória',
            'categoria_id.exists' => 'Categoria inválida',
        ];
    }
}
