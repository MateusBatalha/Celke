<?php

namespace App\adms\Controllers\login;

use App\adms\Views\Services\LoadViewService;

/**
 * Controller login
 *
 * @author Cesar <cesar@celke.com.br>
 */
class Login
{    

    /** @var array|string|null $dados Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = null;

    /**
     * Página login
     * 
     * @return void
     */
    public function index(): void
    {
        
        // Carregar a VIEW
        $loadView = new LoadViewService("adms/Views/login/login", $this->data);
        $loadView->loadView();
        
    }
}
