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
            $telefone = htmlspecialchars($_POST["telefone"]);
            $logradouro = htmlspecialchars($_POST["logradouro"]);
            $numero = htmlspecialchars($_POST["numero"]);
            $bairro = htmlspecialchars($_POST["bairro"]);
            $cidade = htmlspecialchars($_POST["cidade"]);
            $estado = htmlspecialchars($_POST["estado"]);
            $cep = htmlspecialchars($_POST["CEP"]);

        } catch (Exception $e) {
            error_log('Erro no request, campos obrigatórios não foram preenchidos');
            header('pagina registro.php');
        }
        $conexao = conectar_bd();

        $salt = random_bytes(32);
        $criptografada = openssl_pbkdf2($senha, $salt, 32, 600000, 'SHA256');
        $comandoUsuario = "INSERT INTO Usuario (Nome, Salt, Senha_hash, Email, Telefone )  VALUES ('" . $nome . "', '" . $salt . "', '" . $criptografada . "', '" . $email . "' '" . $telefone . "')";
        $resultado_query_usuario = mysqli_query($conexao, $comandoUsuario) or header("Location: login.php");
        $comandoEndereco = "INSERT INTO Endereco (idUsuario, Logradouro, Numero, Bairro, Cidade, Estado, CEP) VALUES (LAST_INSERT_ID(), '" . $logradouro . "', '" . $numero . "', '" . $bairro . "', '" . $cidade . "', '" . $estado . "', '" . $cep . "')";        
        $resultado_query_endereco = mysqli_query($conexao, $comandoEndereco) or header("Location: login.php");
    }
    header('/paginas/registro.php');
?>