<?php

echo "<h3>Listar Usuários</h3>";

echo "<a href='{$_ENV['URL_ADM']}create-user'>Cadastrar</a><br><br>";

// Usar operador ternário para verificar se existe a mensagem de sucesso e erro
echo isset($_SESSION['success']) ? "<p style='color: #086;'>{$_SESSION['success']}</p>" : "";
echo isset($_SESSION['error']) ? "<p style='color: #f00;'>{$_SESSION['error']}</p>" : "";
unset($_SESSION['success'], $_SESSION['error']);

// Acessa o IF quando encontrar o elemento no array users
if (isset($this->data['users'])) {

    // Percorrer o array de usuários
    foreach($this->data['users'] as $user){

        // Extrair o array para imprimir o elemento do array através do nome
        extract($user);

        // Imprimir as informações do registro
        echo "ID: $id<br>";
        echo "Nome: $name<br>";
        echo "E-mail: $email<br>";
        echo "<a href='{$_ENV['URL_ADM']}view-user/$id'>Visualizar</a><br>";
        echo "<a href='{$_ENV['URL_ADM']}update-user/$id'>Editar</a><br>";

        echo "<hr>";
    }
} else { // Acessa o ELSE quando não existir registros
    echo "<p style='color: #f00;'>Nenhum usuário encontrado!</p>";
}
