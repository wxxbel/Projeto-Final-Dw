<?php
    function conectar_bd(){
        $nome_servidor = "127.0.0.1";
        $nome_user = "root";
        $senha = "";
        $nome_bd = "RedeSocial";

        $conexao = mysqli_connect($nome_servidor, $nome_user, $senha);
        $resultado_query = mysqli_query($conexao, "USE " . $nome_bd);
        return $conexao;
    }

    // Código original de https://stackoverflow.com/questions/1354999/keep-me-logged-in-the-best-approach
    function onLogin($user, $lembrar) {
        $token = random_bytes(32); // generate a token, should be 128 - 256 bit
        storeTokenForUser($user, $token);
        $cookie = $user . ':' . $token;
        $mac = hash_hmac('sha256', $cookie, SECRET_KEY);
        $cookie .= ':' . $mac;
        $longevidade = time()+60*60*24*30;
        if (!$lembrar) {$longevidade = 0;}
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
    
    function validar_senha($id_usuario, $senha){
        $conexao = conectar_bd();
        $comando = "SELECT * from Usuario WHERE idUsuario = '" + $id_usuario + "';";
        $resultado_query = mysqli_query($conexao, $comando) or header("Location: login.php");
        if (mysqli_num_rows($resultado_query) >= 1) {
            $usuario = mysqli_fetch_assoc($resultado_query);
            return openssl_pbkdf2($senha, $usuario["Salt"], 32, 600000, 'SHA256') == $usuario["Senha_hash"];
        }
        return false;
    } 

    function guardar_senha($user, $senha) {
        $salt = random_bytes(32);
        $criptografada = openssl_pbkdf2($senha, $salt, 32, 600000, 'SHA256');
        $conexao = conectar_bd();
        $comando = "UPDATE Usuario SET Senha_hash = '" + $criptografada + "', Salt= '" + $salt + "' WHERE idUsuario = '" + $user + "';";
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
    
    function criar_banco() {
        $nome_servidor = "127.0.0.1";
        $nome_user = "root";
        $senha = "";
        $nome_bd = "RedeSocial";        
        
        $conexao = mysqli_connect($nome_servidor, $nome_user, $senha);

        $comando = "CREATE DATABASE IF NOT EXISTS " . $nome_bd;
        $resultado_query = mysqli_query($conexao, $comando);

        $comando="USE " . $nome_bd;
        $resultado_query = mysqli_query($conexao, $comando);

        $comando = "CREATE TABLE Usuario ( 
                        idUsuario INT PRIMARY KEY AUTO_INCREMENT,  
                        Nome VARCHAR(255) NOT NULL,  
                        Salt INT NOT NULL,  
                        Senha_hash INT NOT NULL,  
                        Email VARCHAR(254) NOT NULL UNIQUE,  
                        CPF CHAR(11) NOT NULL UNIQUE,  
                        Telefone CHAR(11)
                    ); 

                    CREATE TABLE Endereco ( 
                        idEndereco INT PRIMARY KEY,  
                        idUsuario INT,  
                        Logradouro VARCHAR(255) NOT NULL,  
                        Numero VARCHAR(255) NOT NULL,  
                        Bairro VARCHAR(255),  
                        Cidade VARCHAR(255),  
                        Estado VARCHAR(255),  
                        CEP CHAR(8),  

                        FOREIGN KEY(idUsuario) REFERENCES Usuario (idUsuario)
                    ); 

                    CREATE TABLE Canal ( 
                        idCanal INT PRIMARY KEY,  
                        Nome VARCHAR(255),  
                        Bio VARCHAR(2048),  
                        Caminho_foto VARCHAR(255),  
                        Caminho_banner VARCHAR(255),  
                        idUsuario INT,  
                        FOREIGN KEY(idUsuario) REFERENCES Usuario (idUsuario)
                    ); 

                    CREATE TABLE Comentario ( 
                        idComentario INT PRIMARY KEY,  
                        Texto VARCHAR(255) NOT NULL,  
                        idCanal INT,  
                        idAutor INT,  
                        FOREIGN KEY(idCanal) REFERENCES Canal (idCanal),
                        FOREIGN KEY(idAutor) REFERENCES Usuario (idUsuario)
                    ); 

                    CREATE TABLE LikeCanal ( 
                        idUsuario INT,  
                        idCanal INT,  
                        Dislike BOOLEAN DEFAULT FALSE,

                        FOREIGN KEY(idCanal) REFERENCES Canal (idCanal),
                        FOREIGN KEY(idUsuario) REFERENCES Usuario (idUsuario),
                        PRIMARY KEY(idUsuario, idCanal)
                    ); 

                    CREATE TABLE LikeComentario ( 
                        idUsuario INT,  
                        idComentario INT,
                        Dislike BOOLEAN DEFAULT FALSE,
                        
                        FOREIGN KEY(idComentario) REFERENCES Comentario (idComentario),
                        FOREIGN KEY(idUsuario) REFERENCES Usuario (idUsuario),
                        PRIMARY KEY(idUsuario, idComentario)
                    );"; 

        $resultado_query=mysqli_query($conexao, $comando);
    }
    
    
    
?>