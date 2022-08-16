<?php

namespace App\Http\Requests;

use App\Http\Requests\FormRequest as ApiFormRequest;

class ResetPassordRequest extends ApiFormRequest
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
            'senha' => 'required|string|min:6|max:15|confirmed',
            'email' => 'email|required',
            'cpf' => 'string|required',
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
            'email' => 'O campo :attribute deve conter um email cadastrado na plataforma!',
            'required' => 'O campo :attribute é de preenchimento obrigatorio!.',
            'min' => "O campo :attribute deve conter no minimo 6 caracteres",
            'max' => "O campo :attribute deve conter no maximo 15 caracteres",
            'confirmed' => 'Os campos Senha e Confirmar Senha dever ser iguais!',
            'string' => "O campo :attribute deve conter letras e numeros!"
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
            "email" => 'E-mail',
            "cpf" => 'CPF',
            "senha" => 'Senha',
        ];
    }
}
