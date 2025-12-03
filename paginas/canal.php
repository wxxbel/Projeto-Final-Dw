<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>perfil</title>
    <link rel="stylesheet" href="../css/perfil.php">
</head>

<body>
    <?php require "cabecalho.php" ?>

    <div class="container-descricao">
        <img src="{{banner_url}}" class="banner" alt="Banner do canal">
        <img src="{{foto_perfil_url}}" class="foto_perfil" alt="Foto do perfil">


        <h1>{{nome_canal}}</h1>
        <p>{{bio_canal}}</p>
    </div>

    <div class="container-canais" id="lista-comentarios">

        <div class="comentario">
            <h3>{{nome_autor}}</h3>
            <p>{{texto_comentario}}</p>


            <div class="reacoes">
                <button class="btn-like">👍 Like <span class="count-like">{{qtd_likes}}</span></button>
                <button class="btn-dislike">👎 Dislike <span class="count-dislike">{{qtd_dislikes}}</span></button>
            </div>
        </div>

    </div>

</body>

</html>