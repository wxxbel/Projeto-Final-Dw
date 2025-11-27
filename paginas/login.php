<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">

    <title>Pagina de Login</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href='https://cdn.boxicons.com/3.0.4/fonts/basic/boxicons.min.css' rel='stylesheet'>
</head>

<body>

    <?php
        session_start();
        if (isset($_SESSION["erro_login"])) {
            echo $_SESSION["erro_login"];
            unset($_SESSION["erro_login"]);
        }
    ?>

    <div class = "wrapper">
        <form method="POST" action="../backend/logar.php" class="form">
            
            <h1>Login</h1>

            <div class = "input-box">
                <div class="efeito">
                    <input required type="email" name = "email">
                    <label for="email">Email</label>
                    <i class='bx  bxs-user'></i> 
                    <br><br>
                </div>
            </div>

            <div class = "input-box">
                <div class="efeito">
                    <input required type = "password" name = "senha">
                    <label for="senha">Senha</label><br><br>
                    <i class='bx  bxs-lock'></i>
                </div>   
            </div>

            <div class = "lembrar-senha">
                <label><input type="checkbox">Lembrar de mim</label>
                <a href="#">Esqueceu a senha?</a>
            </div>

            <button name="botao" type="submit" value="entrar" class="button"><strong>Entrar</strong></button>

            <div class = "cadastro-link">
                <p>Não possui uma conta?
                <a href="cadastro" class="link"><strong>Cadastrar</strong></a>
                </p>
            </div>
        </form>
    </div>
</body>
</html>