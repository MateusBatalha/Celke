<?php 

namespace App\adms\Controllers\Services\Validation;

use Rakit\Validation\Validator;

class ValidationUserRakitService 
{

    /**
     * Validar os dados do formulário.
     *
     * @param array $data Dados do formulário.
     * @return array Lista de erros.
     */
    public function validate(array $data): array
    {

        // Criar o array para receber as mensagens de erro
        $errors = [];

        // Instanciar a classe validar formulário
        $validator = new Validator();

        // Definir as regras de validação
        $validation = $validator->make($data, [
            'name'             => 'required',
            'email'            => 'required|email',
            'password'         => 'required|min:6|regex:/[A-Z]/|regex:/[^\w\s]/',
            'confirm_password' => 'required|same:password',
        ]);

        // Definir mensagens personalizadas
        $validation->setMessages([
            'name:required'             => 'O campo nome é obrigatório.',
            'email:required'            => 'O campo e-mail é obrigatório.',
            'email:email'               => 'O campo e-mail deve ser um email válido.',
            'password:required'         => 'O campo senha é obrigatório.',
            'password:min'              => 'A senha deve ter no mínimo 6 caracteres.',
            'password:regex'            => 'A senha deve ter pelo menos uma letra maiúscula e um caractere especial.',
            'confirm_password:required' => 'Necessário confirmar a senha.',
            'confirm_password:same' => 'A confirmação da senha deve ser igual a senha.',
        ]);

        // Validar os dados 
        $validation->validate();

        // Retornar erros se houver
        if($validation->fails()){

            // Recuperar os erros 
            $arrayErrors = $validation->errors();

            // Percorrer o array de erros 
            // firstOfAll - obter a primeira mensagem de erro para cada campo validado.
            foreach($arrayErrors->firstOfAll() as $key => $message){
                $errors[$key] = $message;
            }
        }

        return $errors;
    }
}