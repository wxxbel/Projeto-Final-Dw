<?php
    function conectar_bd(){
        $nome_servidor = "127.0.0.1";
        $nome_user = "root";
        $senha = "toor";
        $nome_bd = "RedeSocial";

        $conexao = mysqli_connect($nome_servidor, $nome_user, $senha);
        $resultado_query = mysqli_query($conexao, "USE " . $nome_bd);
        return $conexao;
    }

    function gerar_token_login($id_usuario, $lembrar) {
        $token = random_bytes(32);
        $salt = random_bytes(32);
        $token_hash = openssl_pbkdf2($token, $salt, 32, 600000, 'SHA256');
        // Por padrão vale por 30 dias após gerado
        $validade = time()+60*60*24*30;
        if (!$lembrar) {$validade = time();}
        
        $conexao = conectar_bd();
        $comando = "INSERT INTO TokensUsuario (idUsuario, IpCliente, DataEmissao, Salt, Token_hash, Validade)  
                        VALUES ('" . $id_usuario . "', '" . $_SERVER['REMOTE_ADDR'] . "', '" . date('Y-m-d') . "', '" . mysqli_real_escape_string($conexao, $salt) . "', '" . mysqli_real_escape_string($conexao, $token_hash) . "', '" . date('Y-m-d', $validade) . "')";
        $resultado_query = mysqli_query($conexao, $comando);
        
        $cookie = $id_usuario . ':' . $token;
        if (!$lembrar) {$validade = 0;}
        setcookie('rememberme', $cookie, time()+60*60*24*7, "/");
        echo $cookie;
    }

    function checar_login() {
        // Retorna o id ligado ao usuário
        // Retorna -1 se não tem o cookie com o token
        // Retorna -2 se não tem tokens para esse idUsuario
        // Retorna 0 se não o token não corresponde a nenhum token salvo 
        $cookie = isset($_COOKIE['rememberme']) ? $_COOKIE['rememberme'] : '';
        if (!$cookie) {return -2;}
        
        list ($id_usuario, $token) = explode(':', $cookie);
        
        // Puxa os tokens do usuário, se não encontrar nenhum, retorna falso
        $conexao = conectar_bd();
        $comando = "SELECT IpCliente, DATEDIFF(Validade, '" . date('Y-m-d') . "') as DiasAteVencimento, DATEDIFF(Validade, DataEmissao) as TemVencimento, Salt, Token_hash from TokensUsuario WHERE idUsuario = '" . $id_usuario . "';";
        $resultado_query = mysqli_query($conexao, $comando);
        // Checa se há tokens
        if (mysqli_num_rows($resultado_query) === 0) {return -1;}
        
        while ($obj_token = mysqli_fetch_assoc($resultado_query)) {
            // Se o token não tem mais dias até o vencimento, pula o token
            // TODO Remover do banco de dados os tokens que já venceram
            if ($obj_token["DiasAteVencimento"] < 0 and $obj_token["TemVencimento"] !== 0) {continue;}
            // Se o ip da máquina não bate com o ip do token, pula esse token
            if ($obj_token["IpCliente"] !== $_SERVER['REMOTE_ADDR']) {continue;}
            // Checa se os tokens batem
            $token_hash = openssl_pbkdf2($token, $obj_token["Salt"], 32, 600000, 'SHA256');
            if ($token_hash === $obj_token["Token_hash"]) {return $id_usuario;}
        }
        // Se nenhum token válido foi encontrado, retorna falso
        return 0;
    }
    
    function validar_senha($id_usuario, $senha){
        $conexao = conectar_bd();
        $comando = "SELECT * from Usuario WHERE idUsuario = '" . $id_usuario . "';";
        $resultado_query = mysqli_query($conexao, $comando);
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
        $comando = "UPDATE Usuario SET Senha_hash = '" . $criptografada . "', Salt= '" . $salt . "' WHERE idUsuario = '" . $user . "';";
        $resultado_query = mysqli_query($conexao, $comando);
        if (!$resultado_query) {
            error_log('Erro ao tentar salvar senha do usuário ' . $user . ': ' . mysqli_error($conexao));
            return false;
        }
        return true;
    }
    
    function dar_like($id, $tipo, $dislike = false) {
        
        if ($tipo == "perfil" and $tipo != "comentario" ) {

        }
    }

    function idUsuario_para_idCanal($id_usuario) {
        // Se nenhum token válido foi encontrado, retorna 0
        $conexao = conectar_bd();
        $comando = "SELECT idCanal FROM Canal WHERE idUsuario = '" . $id_usuario . "';";
        $resultado_query = mysqli_query($conexao, $comando);
        if (mysqli_num_rows($resultado_query) === 0) {return 0;}
        
        while ($canal = mysqli_fetch_assoc($resultado_query)) {
            return $canal["idCanal"];
        }
        return 0;
    }
    
    
    function criar_banco() {
        $nome_servidor = "127.0.0.1";
        $nome_user = "root";
        $senha = "toor";
        $nome_bd = "RedeSocial";        
        
        $conexao = mysqli_connect($nome_servidor, $nome_user, $senha);

        $comando = "CREATE DATABASE IF NOT EXISTS " . $nome_bd;
        $resultado_query = mysqli_query($conexao, $comando);

        $comando="USE " . $nome_bd;
        $resultado_query = mysqli_query($conexao, $comando);

        $comando = "CREATE TABLE TipoUsuario ( 
                        idTipoUsuario INT PRIMARY KEY,  
                        Nome VARCHAR(255)
                    ); 

                    CREATE TABLE Usuario ( 
                        idUsuario INT PRIMARY KEY AUTO_INCREMENT,  
                        idTipoUsuario INT,  
                        FOREIGN KEY(idTipoUsuario) REFERENCES TipoUsuario (idTipoUsuario),

                        Nome VARCHAR(255) NOT NULL,  
                        Salt BINARY(32) NOT NULL,  
                        Senha_hash BINARY(32) NOT NULL,  
                        Email VARCHAR(254) NOT NULL UNIQUE,  
                        CPF CHAR(11) NOT NULL UNIQUE,  
                        Telefone CHAR(11)
                    ); 

                    CREATE TABLE TokensUsuario ( 
                        idTokensUsuario INT PRIMARY KEY AUTO_INCREMENT,  
                        idUsuario INT,  
                        IpCliente VARCHAR(20) NOT NULL,  
                        DataEmissao DATE NOT NULL,  
                        Salt BINARY(32) NOT NULL,
                        Token_hash BINARY(32) NOT NULL,
                        Validade DATE, 
                        
                        FOREIGN KEY(idUsuario) REFERENCES Usuario (idUsuario)
                    ); 

                    CREATE TABLE Endereco ( 
                        idEndereco INT PRIMARY KEY AUTO_INCREMENT,  
                        idUsuario INT,  
                        Logradouro VARCHAR(255) NOT NULL,  
                        Numero VARCHAR(255) NOT NULL,  
                        Bairro VARCHAR(255),  
                        Cidade VARCHAR(255),  
                        Estado VARCHAR(255),  
                        CEP CHAR(9),  

                        FOREIGN KEY(idUsuario) REFERENCES Usuario (idUsuario)
                    ); 

                    CREATE TABLE Canal ( 
                        idCanal INT PRIMARY KEY AUTO_INCREMENT,  
                        Nome VARCHAR(255),  
                        Bio VARCHAR(2048) DEFAULT \"\",  
                        Caminho_foto VARCHAR(255),  
                        Caminho_banner VARCHAR(255),  
                        idUsuario INT,  
                        FOREIGN KEY(idUsuario) REFERENCES Usuario (idUsuario)
                    ); 

                    CREATE TABLE Comentario ( 
                        idComentario INT PRIMARY KEY AUTO_INCREMENT,  
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
                    );




                    INSERT INTO TipoUsuario (idTipoUsuario, Nome) 
                    VALUES
                        (1, \"Comum\"),
                        (2, \"Moderador\"),
                        (3, \"Administrador\");"; 

        $resultado_query=mysqli_query($conexao, $comando);
    }
    
    
    
?>