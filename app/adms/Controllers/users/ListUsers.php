<?php

namespace App\adms\Controllers\users;

use App\adms\Models\Repository\UsersRepository;
use App\adms\Views\Services\LoadViewService;

/**
 * Controller listar usuários
 *
 * @author Cesar <cesar@celke.com.br>
 */
class ListUsers
{

    /** @var array|string|null $dados Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = null;

    /**
     * Recuperar os últimos usuários
     * 
     * @return void
     */
    public function index()
    {

        // Instanciar o Repository para recuperar os regitros do banco de dados
        $listUsers = new UsersRepository();
        $this->data['users'] = $listUsers->getAllUsers();

        // Carregar a VIEW
        $loadView = new LoadViewService("adms/Views/users/list", $this->data);
        $loadView->loadView();

    }
}
