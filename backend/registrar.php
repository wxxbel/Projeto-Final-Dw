<?php
    require_once 'funcoes.php';
    session_start();
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Pega e sanitiza o usuario e senha digitado
        try {
            $nome = htmlspecialchars($_POST["nome"]);
            $cpf = htmlspecialchars($_POST["cpf"]);
            $email = htmlspecialchars($_POST["email"]);
            $senha = htmlspecialchars($_POST["senha"]);
            $senha_novamente = htmlspecialchars($_POST["senha_novamente"]);
        } catch (Exception $e) {
            error_log('Erro no request, campos obrigatórios não foram preenchidos');
            header('Location: /cadastro');
            exit();
        }
        $conexao = conectar_bd();

        $salt = random_bytes(32);
        $criptografada = openssl_pbkdf2($senha, $salt, 32, 600000, 'SHA256');
        $comando = "INSERT INTO Usuario (idTipoUsuario, Nome, Salt, Senha_hash, Email)  VALUES (1, '" . $nome . "', '" . $salt . "', '" . $criptografada . "', '" . $email . "')";
        $resultado_query = mysqli_query($conexao, $comando) or header('Location: /cadastro');
    }
    header('Location: /cadastro');
    exit();
?>