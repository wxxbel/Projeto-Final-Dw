<?php
    fuction conectar_bd(){
        $nome_servidor = "127.0.0.1";
        $nome_user = "root";
        $senha = "";
        $nome_bd = "bancodedados";

        return mysqli_connect($nome_servidor, $nome_user, $senha);
    }

    // Código original de https://stackoverflow.com/questions/1354999/keep-me-logged-in-the-best-approach
    function onLogin($user, $lembrar) {
        $token = random_bytes(32); // generate a token, should be 128 - 256 bit
        storeTokenForUser($user, $token);
        $cookie = $user . ':' . $token;
        $mac = hash_hmac('sha256', $cookie, SECRET_KEY);
        $cookie .= ':' . $mac;
        $longevidade = time()+60*60*24*30
        if (!$lembrar) {$longevidade = 0}
        setcookie('rememberme', $cookie);
    }

    function rememberMe() {
        $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
        if ($cookie) {
            list ($user, $token, $mac) = explode(':', $cookie);
            if (!hash_equals(hash_hmac('sha256', $user . ':' . $token, SECRET_KEY), $mac)) {
                return false;
            }
            $usertoken = fetchTokenByUserName($user);
            if (hash_equals($usertoken, $token)) {
                logUserIn($user);
            }
        }
    }
    // Final do código do stackoverflow
    
    function validar_senha($user, $senha){
        return true;
    } 

    function guardar_senha($user, $senha) {
        $salt = random_bytes(32);
        $criptografada = openssl_pbkdf2($senha, $salt, 32, 600000, 'SHA256');
        $conexao = conectar_bd();
        $comando = "UPDATE Customers SET Senha_hash = '" + $criptografada + "', Salt= '" + $salt + "' WHERE Id_usuario = '" + $user + "';";
        $resultado_query = mysqli_query($conexao, $comando);
        if (!$resultado_query) {
            error_log('Erro ao tentar salvar senha do usuário ' + $user + ': ' + mysqli_error($conexao));
            return false;
        }
        return true;
    }
    
    function dar_like($id, $tipo, $dislike = false) {
        
        if ($tipo == "perfil" and $tipo != "comentario" ) {

        }
    }
    
    /*
    $comando = "CREATE DATABASE IF NOT EXISTS " . $nome_bd;
    $resultado_query=mysqli_query($conexao, $comando);

    $comando="USE " . $nome_bd;
    $resultado_query = mysqli_query($conexao, $comando);

    $comando = "CREATE TABLE IF NOT EXISTS Usuario(
    nome VARCHAR(30) PRIMARY KEY,
    senha VARCHAR(6) NOT NULL)";
    $resultado_query=mysqli_query($conexao, $comando);
    
    $comando = "INSERT IGNORE INTO Usuario(nome, senha) VALUES ('bel', '2')";
    $resultado_query = mysqli_query($conexao, $comando);
    */
    
    
    
?>