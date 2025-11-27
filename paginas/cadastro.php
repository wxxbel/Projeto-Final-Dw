<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE-edge">

    <title>Pagina de cadastro</title>
    <link rel="stylesheet" href="../css/cadastro.css">
</head>

<body>

    <?php
        session_start();
        if (isset($_SESSION["erro_registrar"])) {
            echo $_SESSION["erro_registrar"];
            unset($_SESSION["erro_registrar"]);
        }
    ?>

    <div class="wrapper">
        
        <form method="POST" action="../backend/registrar.php">
            
            <h1>Criar uma conta</h1>

            <div class="input-box">
                <input name="nome" placeholder="Nome completo" required>
            </div>

            <div class="input-box">
                <input name="cpf" placeholder="CPF" required>
            </div>

            <div class="input-box">
                <input name="email" type="email" placeholder="Email" required>
            </div>

            <div class="input-box">
                <input name="senha" type="password" placeholder="Senha" required>
            </div>

            <div class="input-box">
                <input name="senha_novamente" type="password" placeholder="Confirme a senha" required>
            </div>
            
            <button name="botao" type="submit" value="entrar" class="btn">Criar</button>
            

            <div class="conta-link">
                <p>Já tem uma conta?
                <a href="login">Clique aqui!</a></p>
            </div>
        </form>
    </div>
</body>
</html>