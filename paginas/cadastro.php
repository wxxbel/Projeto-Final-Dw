<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <?php
        session_start();
        if (isset($_SESSION["erro_registrar"])) {
            echo $_SESSION["erro_registrar"];
            unset($_SESSION["erro_registrar"]);
        }
    ?>

    <form method="POST" action="/backend/registrar.php">
        Nome Completo*: <input name="nome" required> <br>
        CPF*: <input name="cpf" required> <br>
        Email*: <input name="email" type="email" required> <br>
        Senha*: <input name="senha" type="password" required> <br>
        Repita a senha*: <input name="senha_novamente" type="password" required> <br>
        <button name="botao" type="submit" value="logar">Registrar</button>
        <a href="login.php">Já tem uma conta? <strong>Clique aqui!</strong></a>
    </form>
    
</body>
</html>