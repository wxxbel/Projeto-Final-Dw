<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href=".vs\estilo\style.css">
</head>
<body>

    <?php 
        require_once 'funcoes.php';
        session_start();
        if (isset($_SESSION["erro_login"])) {
            echo $_SESSION["erro_login"];
            unset($_SESSION["erro_login"]);
        }
    ?>

    <form method="POST" action="logar.php" class="form">
        <h1>LOGIN</h1>
        <p class="email">Email:</p> <input name="email" required class="name"> <br>
        <br>
        <p class="email">Senha:</p> <input name="senha" type="password" required class="senha"> <br><br>
        <button name="botao" type="submit" value="logar" class="button"><strong>Logar</strong></button>
        <a href="cadastro.php" class="link"><strong>cadastro</strong></a>
    </form>
    
</body>
</html>