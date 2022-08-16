<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest as ApiFormRequest;

class UserRequest extends ApiFormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'nome' => 'required|max:255',
            'cpf' => 'required|string',
            'email' => 'required|email',
            'senha' => 'required|min:6|max:15',
            'termo' => 'boolean'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'required' => 'O campo :attribute é de preenchimento obrigatório!',
            'email.email' => 'o campo :attribute deve conter um email.',
            'senha.min' => 'O campo :attribute deve ter entre 6 e 15 caracteres.',
            'senha.max' => 'O campo :attribute deve ter entre 6 e 15 caracteres.',
            'termo.boolean' => "O termo deve ser aceito para efetuar o cadastro",
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'nome' => 'Nome',
            'email' => 'Email',
            'cpf' => 'CPF',
            'senha' => "Senha",
        ];
    }
}
