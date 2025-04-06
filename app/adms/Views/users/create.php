<?php

use App\adms\Helpers\CSRFHelper;

echo "<h3>Cadastrar Usuário</h3>";

echo "<a href='{$_ENV['URL_ADM']}list-users'>Listar</a><br><br>";

// Usar operador ternário para verificar se existe a mensagem de sucesso e erro
echo isset($_SESSION['success']) ? "<p style='color: #086;'>{$_SESSION['success']}</p>" : "";
echo isset($_SESSION['error']) ? "<p style='color: #f00;'>{$_SESSION['error']}</p>" : "";
unset($_SESSION['success'], $_SESSION['error']);

// Acessa o IF quando encontrar o elemento no array errors
if(isset($this->data['errors'])){
    foreach($this->data['errors'] as $error){

        echo "<p style='color: #f00;'>$error</p>";
    }
}

?>

<form action="" method="POST">
    <input type="hidden" name="csrf_token" value="<?php echo CSRFHelper::generateCSRFToken('form_create_user'); ?>" >

    <!-- Operador de coalescência nula em PHP (??) - Serve para fornecer um valor padrão se uma determinada chave não estiver presente ou for nula. -->
    <label for="name">Nome: </label>
    <input type="text" name="name" id="name" placeholder="Nome completo" value="<?php echo $this->data['form']['name'] ?? ''; ?>"><br><br>

    <label for="email">E-mail: </label>
    <input type="email" name="email" id="email" placeholder="Melhor e-mail" value="<?php echo $this->data['form']['email'] ?? ''; ?>"><br><br>

    <label for="password">Senha: </label>
    <input type="password" name="password" id="password" placeholder="Senha com mínimo 6 caracteres" value="<?php echo $this->data['form']['password'] ?? ''; ?>"><br><br>

    <label for="confirm_password">Confirmar senha: </label>
    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirme a senha" value="<?php echo $this->data['form']['confirm_password'] ?? ''; ?>"><br><br>

    <button type="submit">Cadastrar</button>

</form>