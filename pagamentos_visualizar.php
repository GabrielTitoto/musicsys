<?php
$admin = false;
include './function/valida_login.php';
include './function/connection.php';

$id = intval($_GET['id']);

if ($_SESSION["usuario"]["admin"]) {
    $req = $conn->prepare('SELECT P.id, U.nome as usuario, U.email, I.nome as curso, S.significado, S.descricao, P.transacao, P.vencimento FROM pagamentos P, usuarios U, cursos I, status_pagamento S WHERE P.id = :id AND P.usuario_id = U.id AND P.curso_id = I.id AND P.status = S.id');
} else {
    $req = $conn->prepare('SELECT P.id, U.nome as usuario, U.email, I.nome as curso, S.significado, S.descricao, P.transacao, P.vencimento FROM pagamentos P, usuarios U, cursos I, status_pagamento S WHERE P.id = :id AND P.usuario_id = U.id AND P.curso_id = I.id AND P.status = S.id AND U.id = :usuario_id');
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
        <title>Pagamentos - MusicSys</title>
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
                        <h1 class="page-header">Pagamentos - Visualizar</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Pagamentos
                                <a href="pagamentos_listar.php" class="btn btn-info btn-panel-heading">Voltar</a>
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
                                                    <p class="form-control-static"><?php echo utf8_encode($linha['usuario']); ?></p>
                                                </div>
                                                <div class="form-group">
                                                    <label>E-mail</label>
                                                    <p class="form-control-static"><?php echo $linha['email']; ?></p>
                                                </div>
                                                <div class="form-group">
                                                    <label>Curso</label>
                                                    <p class="form-control-static"><?php echo utf8_encode($linha['curso']); ?></p>
                                                </div>
                                                <div class="form-group">
                                                    <label>Transação</label>
                                                    <p class="form-control-static"><?php echo $linha['transacao']; ?></p>
                                                </div>
                                                <div class="form-group">
                                                    <label>Vencimento</label>
                                                    <p class="form-control-static"><?php echo $linha['vencimento']; ?></p>
                                                </div>
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <p class="form-control-static"><?php echo utf8_encode($linha['significado']); ?></p>
                                                    <p class="form-control-static"><?php echo utf8_encode($linha['descricao']); ?></p>
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