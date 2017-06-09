<?php
$admin = false;
include './function/valida_login.php';
include './function/connection.php';

$id = intval($_GET['id']);
if ($_SESSION["usuario"]["admin"]) {
    $req = $conn->prepare('SELECT M.id as id, M.nome as nome, M.descricao, U.id idUsuario, U.nome nomeUsuario FROM materiais M, usuarios U WHERE M.id = :id AND M.usuario_id = U.id');
} else {
    $req = $conn->prepare('SELECT M.id as id, M.nome as nome, M.descricao, U.id idUsuario, U.nome nomeUsuario FROM materiais M, usuarios U WHERE M.id = :id AND M.usuario_id = U.id AND M.usuario_id = :usuario_id');
    $req->bindValue(':usuario_id', $_SESSION["usuario"]["id"], PDO::PARAM_INT);
}
$req->bindValue(':id', $id, PDO::PARAM_INT);
$req->execute();
$linha = $req->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Materiais - MusicSys</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/metisMenu.min.css" rel="stylesheet">
        <link href="css/sb-admin-2.css" rel="stylesheet">
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <link href="css/estilo.css" rel="stylesheet" type="text/css">
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>

        <div id="wrapper">
            <?php include('./include/header.php'); ?>

            <div id="page-wrapper">
                <div class="row">
                    <div class="col-lg-12">
                        <h1 class="page-header">Materiais - Visualizar</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Materiais
                                <a href="materiais_listar.php" class="btn btn-info btn-panel-heading">Voltar</a>
                            </div>
                            <div class="panel-body">
                                <?php
                                if ($req->rowCount()) {
                                    ?>
                                    <div class="row">
                                        <div class="col-lg-12">
                                            <form role="form" method="post">
                                                <div class="form-group">
                                                    <label>Id</label>
                                                    <p class="form-control-static"><?php echo $linha['id']; ?></p>
                                                </div>
                                                <div class="form-group">
                                                    <label>Aluno</label>
                                                    <p class="form-control-static"><?php echo $linha['idUsuario'] . " - " . utf8_encode($linha['nomeUsuario']); ?></p>
                                                </div>
                                                <div class="form-group">
                                                    <label>Nome</label>
                                                    <p class="form-control-static"><?php echo utf8_encode($linha['nome']); ?></p>
                                                </div>
                                                <div class="form-group">
                                                    <label>Descrição</label>
                                                    <p class="form-control-static"><?php echo utf8_encode($linha['descricao']); ?></p>
                                                </div>
                                                <div class="form-group">
                                                    <label>Materiais</label>
                                                    <div class="clearfix"></div>
                                                    <?php
                                                    $pasta = "materiais/" . $linha['id'] . "/";
                                                    if (is_dir($pasta)) {
                                                        $diretorio = dir($pasta);
                                                        echo '<ul>';
                                                        while (($arquivo = $diretorio->read()) !== false) {
                                                            if ($arquivo !== '.' && $arquivo !== '..') {
                                                                echo '<li>';
                                                                echo '<a download=' . $pasta . $arquivo . ' href=' . $pasta . $arquivo . '>' . $pasta . $arquivo . '</a>';
                                                                echo '</li>';
                                                            }
                                                        }
                                                        echo '</ul>';
                                                        $diretorio->close();
                                                    }
                                                    ?>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                    <?php
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <script src="js/jquery.min.js"></script>
        <script src="js/bootstrap.min.js"></script>
        <script src="js/metisMenu.min.js"></script>
        <script src="js/sb-admin-2.js"></script>
    </body>
</html>