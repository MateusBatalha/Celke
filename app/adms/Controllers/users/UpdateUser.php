<?php

namespace App\adms\Controllers\users;

use App\adms\Helpers\CSRFHelper;
use App\adms\Helpers\GenerateLog;
use App\adms\Models\Repository\UsersRepository;
use App\adms\Views\Services\LoadViewService;

/**
 * Controller editar usuário
 *
 * @author Cesar <cesar@celke.com.br>
 */
class UpdateUser
{

    /** @var array|string|null $dados Recebe os dados que devem ser enviados para VIEW */
    private array|string|null $data = null;
//-----------------------------------------------------------------------------------------------------------------

     /**
     * Editar o usuário
     * 
     * @param int|string $id id do usuário
     * @return void
     */
    public function index(int|string $id): void
    {

        // Receber os dados do formulário
        $this->data['form'] = filter_input_array(INPUT_POST, FILTER_DEFAULT);

         // Acessar o IF se existir o CSRF e for valido o CSRF
         if (isset($this->data['form']['csrf_token']) and CSRFHelper::validateCSRFToken('form_create_user', $this->data['form']['csrf_token'])) {

            // chamar o método editar
            $this->editUser();
        
        }else {
            // Instanciar o Repository para recuperar o regitro do banco de dados
            $viewUser = new UsersRepository();
            $this->data['form'] = $viewUser->getUser((int) $id);

            // Verificar se encontrou o registro no banco de dados
            if (!$this->data['form']) {

            // Chamar o método para salvar o log
            GenerateLog::generateLog("error", "Usuário não encontrado", ['id' => (int) $id]);

            // Criar a mensagem de erro
            $_SESSION['error'] = "Usuário não encontrado!";

            // Redirecionar o usuário para página listar
            header("Location: {$_ENV['URL_ADM']}list-users");
            return;
        }

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
        $this->data['title_head'] = "Editar Usuário";

        // Carregar a VIEW
        $loadView = new LoadViewService("adms/Views/users/update", $this->data);
        $loadView->loadView();
    }


    /**
     * Editar usuário
     * 
     * Este método realiza a edição de um usuário existente no sistema. Ele valida os dados do formulário usando 
     * a classe 'ValidationUserRakitService', exibe a view com os erros caso existam campos com dados 
     * incorretos,
     * chama o repositório para atualizar o usuário e, dependendo do resultado, redireciona o usuário ou exibe 
     * uma mensagem de erro.
     * 
     * @return void
     */
    private function editUser(): void 
    {
        
        // Instanciar o repository para editar o usuário no banco 
        $userUpdate = new UsersRepository();
        $result = $userUpdate->updateUser($this->data['form']);

        // Acessa o if se o repository retornou true 
        if($result){

            // Criar a mensagem de sucesso
            $_SESSION['success'] = "Usuário editado com sucesso!";

            // Redirecionar o usuário para página listar
            header("Location: {$_ENV['URL_ADM']}list-users");
            return;
        } else {
             // Criar a mensagem de erro
            $this->data['errors'][] = "Usuário não editado!";

            // Chamar o método carregar a view
            $this->viewUser();
        }

    }

   
}
