<?php

namespace App\adms\Models\Repository;

use App\adms\Helpers\GenerateLog;
use App\adms\Models\Services\DbConnection;
use Exception;
use PDO;

/**
 * Repository responsável em buscar os usuários no banco de dados
 *
 * @author Celke
 */
class UsersRepository extends DbConnection
{

    /**
     * Recuperar os usuários
     * @return array Usuários recuperado do banco de dados
     */
    public function getAllUsers()
    {
        // QUERY para recuperar os registros do banco de dados
        $sql = 'SELECT id, name, email 
                FROM adms_users
                ORDER BY id DESC';

        // Preparar a QUERY
        $stmt = $this->getConnection()->prepare($sql);

        // Executar a QUERY
        $stmt->execute();

        // Ler os registros e retornar 
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Recuperar o usuário
     * @return array|bool Usuário recuperado do banco de dados
     */
    public function getUser(int $id): array|bool
    {

        // QUERY para recuperar o registro do banco de dados
        $sql = 'SELECT id, name, email, username, created_at, updated_at
                FROM adms_users
                WHERE id = :id
                ORDER BY id DESC';

        // Preparar a QUERY
        $stmt = $this->getConnection()->prepare($sql);

        // Substituir o link da QUERY pelo valor
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        // Executar a QUERY
        $stmt->execute();

        // Ler o registro e retornar 
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Cadastrar novo usuário
     * @param array $data Dados do usuário
     * @return bool Sucesso ou falha
     */
    public function createUser(array $data): bool
    {

        // Usar try e catch para gerenciar exceção/erro
        try { // Permanece no try se não houver nenhum erro

            // QUERY cadastrar usuários
            $sql = 'INSERT INTO adms_users (name, email, username, password, created_at) VALUES (:name, :email, :username, :password, :created_at)';

            // Preparar a QUERY
            $stmt = $this->getConnection()->prepare($sql);

            // Substituir os links da QUERY pelo valor
            $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindValue(':username', $data['email'], PDO::PARAM_STR);
            $stmt->bindValue(':password', password_hash($data['password'], PASSWORD_DEFAULT));
            $stmt->bindValue(':created_at', date("Y-m-d H:i:s"));

            // Executar a QUERY
            return $stmt->execute();
        } catch (Exception $e) { // Acessa o catch quando houver erro no try

            // Chamar o método para salvar o log
            GenerateLog::generateLog("error", "Usuário não cadastrado.", ['email' => $data['email'], 'error' => $e->getMessage()]);

            return false;
        }
    }

    /**
     * Editar os dados do usuário
     * @param array $data Dados atualizados do usuário
     * @return bool Sucesso ou falha
     */
    public function updateUser(array $data): bool
    {
        // Usar o try e catch para gerenciar exceção/erro
        try { // permance no try se não houver nenhum erro

            // QUERY para atualizar usuário no banco
            $sql = "UPDATE adms_users SET name = :name, email = :email, username = :username, updated_at = :updated_at";

            // Verificar se a senha está incluída nos dados e, se sim, adicionar ao SQL
            if(!empty($data['password'])){
                $sql .= ', password = :password';
            }

            // condição para indicar qual registro editar
            $sql .= ' WHERE id = :id';

            // preparar a QUERY
            $stmt = $this->getConnection()->prepare($sql);

            // Substituir os links da QUERY pelo valor
            $stmt->bindValue(':name', $data['name'], PDO::PARAM_STR);
            $stmt->bindValue(':email', $data['email'], PDO::PARAM_STR);
            $stmt->bindValue(':username', $data['username'], PDO::PARAM_STR);
            $stmt->bindValue(':updated_at', date("Y-m-d H:i:s"));
            $stmt->bindValue(':id', $data['id'], PDO::PARAM_INT);

            // Substituir o link da senha se a mesma estiver presente
            if(!empty($data['password'])){
                $stmt->bindValue(':password', password_hash($data['password'], PASSWORD_DEFAULT));
            }

            // Executar a QUERY
            $stmt->execute();

            // Receber a quantidade de linhas afetadas
            $affectedRows = $stmt->rowCount();

            // Verificar o número de linhas afetadas
            if($affectedRows > 0){
                return true;
            } else {
                // Chamar o método para salvar o log
                GenerateLog::generateLog("error", "Usuário não editado.", ['email' => $data['id']]);

                return false;
            }

        } catch(Exception $e){ // Acessa o catch quando houver erro no try

            // Chamar o método para salvar o log
            GenerateLog::generateLog("error", "Usuário não editado.", ['email' => $data['id'], 'error' => $e->getMessage()]);

            return false;
        }
    }
}
