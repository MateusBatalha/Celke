<?php

namespace App\adms\Controllers\Services\Validation;

use App\adms\Helpers\GenerateLog;
use App\adms\Models\Repository\UniqueValueRepository;
use Exception;
use Rakit\Validation\Rule;

class UniqueRule extends Rule 
{

    // Mensagem de erro genérica
    protected $message = ":attribute :value has been used";

    // Parâmetros dinâmicos
    protected $fillableParams = ['table', 'column', 'except'];

    public function check($value): bool 
    {
        // usar o try catch para gerenciar exceções/erro
        try { // Permanece no try se não houver nenhum erro

        // Verificar se os parâmetros necessários existem
        $this->requireParameters(['table', 'column']);

        // Recuperar os parâmetros
        $column = $this->parameter('column');
        $table = $this->parameter('table');
        $except = $this->parameter('except');

        if ($except AND $except == $value) {
            return true;
        }

        //Instanciar o Repository para verificar se existe registro valor fornecido
        $validationUniqueValue = new UniqueValueRepository();
        return $validationUniqueValue->getRecord($table, $column, $value);

        } catch(Exception $e){
            // Chamar o método para salvar o log
            GenerateLog::generateLog("error", "Usuário não cadastrado.", ['error' => $e->getMessage()]);

            return false;
        }
    }

}