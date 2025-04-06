<?php

namespace App\adms\Controllers\Services\Validation;

use App\adms\Helpers\GenerateLog;
use App\adms\Models\Repository\UniqueValueRepository;
use Exception;
use Rakit\Validation\Rule;

class UniqueInColumnsRule extends Rule 
{

    // Mensagem de erro genérica
    protected $message = ":attribute :value has been used";

    // Parâmetros dinâmicos
    protected $fillableParams = ['table', 'columns', 'except'];

    public function check($value): bool 
    {
        // usar o try catch para gerenciar exceções/erro
        try { // Permanece no try se não houver nenhum erro

        // Verificar se os parâmetros necessários existem
        $this->requireParameters(['table', 'columns']);

        // Recuperar os parâmetros
        $table = $this->parameter('table');
        $columns = explode(';', $this->parameter('columns')); // espera-se que as colunas sejam uma string separada por ponto e vírgula.
        $except = $this->parameter('except');

        if ($except AND $except == $value) {
            return true;
        }

        //Instanciar o Repository para verificar se existe registro valor fornecido
        $validationUniqueValue = new UniqueValueRepository();

        foreach($columns as $column){
            // Verificar se existe registro do valor fornecido
            if(!$validationUniqueValue->getRecord($table, $column, $value))
                return false;
        }
        return true;

        } catch(Exception $e){
            // Chamar o método para salvar o log
            GenerateLog::generateLog("error", "Usuário não cadastrado.", ['error' => $e->getMessage()]);

            return false;
        }
    }

}