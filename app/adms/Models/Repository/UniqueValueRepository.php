<?php

namespace App\adms\Models\Repository;

use App\adms\Models\Services\DbConnection;
use PDO;

/**
 * Repository responsável em verificar se existe um registro com dados fornecidos
 * 
 * @author Mateus
 */
class UniqueValueRepository extends DbConnection
{

    /**
     * Recuperar o registro com dados fornecidos
     * @return bool Retornar falso se o valor fornecido já estiver cadstrado, verdadeiro caso contrário
     */
    public function getRecord($table, $column, $value)
    {

        // QUERY para recuperar o registro do banco de dados 
       $sql = "SELECT COUNT(id) as count FROM `{$table}` WHERE `{$column}` = :value";

       // Preparar a QUERY
       $stmt = $this->getConnection()->prepare($sql);

       // Substituir os links da QUERY pelo valor
       $stmt->bindParam(':value', $value, PDO::PARAM_STR);

       // Executar a QUERY
       $stmt->execute();

       // retorna falso se o valor em fetchColumn não for igual a 0 encontrando um email já cadastrado, caso contrário, retorna true.
       return $stmt->fetchColumn() === 0;
    }
}