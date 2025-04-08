<?php

namespace App\adms\Controllers\users;

use App\adms\Controllers\Services\Validation\ValidationEmptyFieldService;
use App\adms\Controllers\Services\Validation\ValidationUserRakitService;
use App\adms\Controllers\Services\Validation\ValidationUserService;
use App\adms\Helpers\CSRFHelper;
use App\adms\Models\Repository\UsersRepository;
use App\adms\Views\Services\LoadViewService;

/**
 * Controller cadastrar usuário
 *
 * @author Cesar <cesar@celke.com.br>
 */
class CreateUser
{

    /** @var array|string|null $dados Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = null;

    public function index(): void
    {

        // Receber os dados do formulário
        $this->data['form'] = filter_input_array(INPUT_POST, FILTER_DEFAULT);

        // Acessar o IF se existir o CSRF e for valido o CSRF
        if (isset($this->data['form']['csrf_token']) and CSRFHelper::validateCSRFToken('form_create_user', $this->data['form']['csrf_token'])) {

            // Chamar o método cadastrar
            $this->addUser();
        } else {
            // Chamar o método carregar a view
            $this->viewUser();
        }
    }

    /**
     * Instanciar a classe responsável em carregar a View e enviar os dados para View.
     * 
     */
    private function viewUser(): void
    {
        // Criar o título da página
        $this->data['title_head'] = "Cadastrar Usuário";

        // Carregar a VIEW
        $loadView = new LoadViewService("adms/Views/users/create", $this->data);
        $loadView->loadView();
    }

    private function addUser(): void
    {
        // Instanciar a classe validar os dados do formulário cadastrar usuário
        // $validationUser = new ValidationUserService();
        // $this->data['errors'] = $validationUser->validate($this->data['form']);

        // Instanciar a classe validar os dados do formulário se há algum campo vazio
        //$validationUser = new ValidationEmptyFieldService();
        //$this->data['errors'] = $validationUser->validate($this->data['form']);

        // Instanciar a classe validar os dados do formulário com Rakit
        $validationUser = new ValidationUserRakitService();
        $this->data['errors'] = $validationUser->validate($this->data['form']);

        // Acessa o IF quando existir campo com dados incorretos
        if (!empty($this->data['errors'])) {

            // Chamar o método carregar a view
            $this->viewUser();

            return;
        }

        // Instanciar o Repository para criar o usuário
        $userCreate = new UsersRepository();
        $result = $userCreate->createUser($this->data['form']);

        // Acessa o IF se o repository retornou TRUE
        if ($result) {

            // Criar a mensagem de sucesso
            $_SESSION['success'] = "Usuário cadastrado com sucesso!";

            // Redirecionar o usuário para página listar
            header("Location: {$_ENV['URL_ADM']}list-users");
            return;
        } else {
            // Criar a mensagem de erro
            // $_SESSION['error'] = "Usuário não cadastrado!";
            $this->data['errors'][] = "Usuário não cadastrado!";

            // Chamar o método carregar a view
            $this->viewUser();
        }
    }
}
