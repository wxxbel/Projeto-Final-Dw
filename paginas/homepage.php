<!DOCTYPE html>
<html lang="pt-br">

<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="IE-edge">

        <link rel="stylesheet" href="/css/homepage_css/blocks/layout.css">
        <link rel="stylesheet" href="/css/homepage_css/blocks/canal.css">

        <title>Homepage</title>
    </head>

    <body>

        <!-- layout da pagina -->
        <div class="layout">

            <?php require("barra_lateral_esquerda.php");?>

            <!-- layout do main -->
            <div class="layout_main">
                <?php 
                    $conexao = conectar_bd();
                    $comando = "SELECT * FROM Canal";
                    $resultado_query = mysqli_query($conexao, $comando);
                    if (mysqli_num_rows($resultado_query) !== 0) {
                        // Loop que escreve os cards de cada canal
                        // Observação importante que ele é aberto dentro dessa tag php e fechado apenas na tag mais abaixo
                        while ($canal = mysqli_fetch_assoc($resultado_query)) {
                            if ($canal["Caminho_foto"] == "") {
                                $canal["Caminho_foto"] = "/imagens/perfil padrao.jpg";
                            }
                ?>
                <!-- card de cada canal -->
                <?= "<div class=\"tweet\">
                        <img class=\"tweet__autor-logo\" src=\"{$canal["Caminho_foto"]}\" alt=\"Foto do {$canal["Nome"]}\"/>

                        <div class=\"tweet__main\">
                            <div class=\"tweet__header\">
                                <div class=\"tweet__author-name\">
                                    {$canal["Nome"]}
                                </div>
                            </div>
                        </div>
                        <div class=\"tweet__content\">
                            {$canal["Bio"]}
                        </div>
                    </div>"
                ?>
                <?php }}?>

            </div>
            
            <!-- layout da barra lateral direita -->
            <!-- TODO Não ta ficando na lateral!! -->
            <?php require("barra_lateral_direita.php");?>
        </div>
    </body>
</html>