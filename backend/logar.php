<?php
    require_once 'funcoes.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Pega e sanitiza o usuario e senha digitado
        try {
            $email = htmlspecialchars($_POST["email"]);
            $senha = htmlspecialchars($_POST["senha"]);
        } catch (Exception $e) {
            error_log('Erro no request, usuario e/ou senha não estão presentes');
            header('../paginas/login.php');
        }

        $conexao = conectar_bd();

        session_start();

        // Pega o usuário no banco de dados
        $comando = "SELECT * FROM Usuario  WHERE Email = '" . $email . "'";
        $resultado_query = mysqli_query($conexao, $comando) or header("Location: /login");
        if (mysqli_num_rows($resultado_query) === 0) {
            $_SESSION["erro_login"] = "Email ou senha incorretos";
            header("Location: /login");
            exit();
        }

        $usuario_salvo = mysqli_fetch_assoc($resultado_query);
        if (validar_senha($usuario_salvo["idUsuario"], $senha)){
            $_SESSION['usuario'] = $usuario_salvo["nome"];
            header("Location: /login");
            exit();
        }
        else{
            $_SESSION["erro_login"] = "Email ou senha incorretos";
            header("Location: /login");
            exit();
        }
    }
?>