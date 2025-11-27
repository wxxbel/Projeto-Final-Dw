<?php
    require 'backend/funcoes.php';
    // Código derivado do exemplo do próprio FastRoute
    require 'vendor/autoload.php';

    $dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {
        $r->addRoute('GET', '/', 'get_tela_principal');
        $r->addRoute('GET', '/login', 'get_tela_login');
        $r->addRoute('GET', '/cadastro', 'get_tela_cadastro');

        $r->addRoute('GET', '/canal/{id:\d+}', 'get_tela_canal');
    });
    
    $httpMethod = $_SERVER['REQUEST_METHOD'];
    $uri = $_SERVER['REQUEST_URI'];
    if (false !== $pos = strpos($uri, '?')) {
        $uri = substr($uri, 0, $pos);
    }
    $uri = rawurldecode($uri);

    $routeInfo = $dispatcher->dispatch($httpMethod, $uri);
    switch ($routeInfo[0]) {
        case FastRoute\Dispatcher::NOT_FOUND:
            // ... 404 Not Found
            echo "404";
            break;
        case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
            $allowedMethods = $routeInfo[1];
            // ... 405 Method Not Allowed
            echo "405";
            break;
        case FastRoute\Dispatcher::FOUND:
            $handler = $routeInfo[1];
            $vars = $routeInfo[2];
            $id_usuario = checar_login();                   
            $id_meu_canal = idUsuario_para_idCanal($id_usuario);

            switch ($handler) {
                case "get_tela_principal":
                    if ($id_usuario > 0) {
                        require 'paginas/principal.php';
                        break;
                    } else {
                        header('Location: /login');
                        exit();
                    }
                    break;
                case "get_tela_login":
                    require 'paginas/login.php';
                    break;
                case "get_tela_cadastro":
                    require 'paginas/cadastro.php';
                    break;
                case "get_tela_canal":
                    
                    if ($id_usuario > 0) { 
                        if ($id_meu_canal !== $vars["id"]) 
                            {require 'paginas/canal.php';}
                        else 
                            {require 'paginas/canal_edicao.php';}
                        break;
                    } else {
                        header('Location: /login');
                        exit();
                    }
            }
    }
?>