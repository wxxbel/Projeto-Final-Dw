<?php
    require_once 'funcoes.php';
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Pega e sanitiza o usuario e senha digitado
        try {
            $usuario = htmlspecialchars($_POST["usuario"]);
            $senha = htmlspecialchars($_POST["senha"]);
        } catch (Exception $e) {
            error_log('Erro no request, usuario e/ou senha não estão presentes');
            header('login.php');
        }

        $conexao = conectar_bd();

        session_start();

        // Pega o usuário no banco de dados
        $comando = "SELECT * FROM Usuario  WHERE Id_usuario = '" . $usuario . "'";
        $resultado_query = mysqli_query($conexao, $comando) or header("Location: login.php");
        // Se não encontrou o usuário, procurar pelo email
        if (mysqli_num_rows($resultado_query) < 1)     {
            $comando = "SELECT * FROM Usuario  WHERE Email = '" . $usuario . "'";
            $resultado_query = mysqli_query($conexao, $comando) or header("Location: login.php");
            // Se também não achou pelo email, adiciona o erro no session e envia o usuário para a página de login
            if (mysqli_num_rows($resultado_query) < 1)     {
                $_SESSION["erro_login"] = "Usuário não encontrado";
                header("Location: login.php");
            }
        }

        $usuario_salvo = mysqli_fetch_assoc($resultado_query);
        if ($usuario_salvo["senha"] == $senha){
            $_SESSION['usuario'] = $usuario_salvo["nome"];
            header("Location: menu.php");
            exit();
        }
        else{
            echo "Senha errada!";
        }
    }
?>