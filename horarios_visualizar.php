<?php
$admin = false;
include './function/valida_login.php';
include './function/connection.php';

$id = intval($_GET['id']);
$req = $conn->prepare('SELECT H.id as id, U.nome as usuario, I.nome as curso, H.data, H.hora, H.local FROM horarios H, usuarios U, cursos I WHERE H.id = :id AND H.usuario_id = U.id AND H.curso_id = I.id');
$req->execute(array('id' => $id));
$linha = $req->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Horários - MusicSys</title>
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
                        <h1 class="page-header">Horários - Visualizar</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Horários
                                <a href="horarios_listar.php" class="btn btn-info btn-panel-heading">Voltar</a>
                            </div>
                            <div class="panel-body">
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
                                                <label>Curso</label>
                                                <p class="form-control-static"><?php echo utf8_encode($linha['curso']); ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label>Data</label>
                                                <p class="form-control-static"><?php echo $linha['data']; ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label>Hora</label>
                                                <p class="form-control-static"><?php echo $linha['hora']; ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label>Local</label>
                                                <p class="form-control-static"><?php echo utf8_encode($linha['local']); ?></p>
                                            </div>
                                            <?php
                                            if ($_SESSION["usuario"]["admin"]) {
                                                ?>
                                                <a href="horarios_editar.php?id=<?php echo $id; ?>" class="btn btn-primary">Editar</a>
                                                <a href="horarios_deletar.php?tipo=unico&id=<?php echo $id; ?>" onclick="return confirm('Deseja realmente excluir?')" class="btn btn-danger">Excluir este evento</a>
                                                <a href="horarios_deletar.php?tipo=todos&id=<?php echo $id; ?>" onclick="return confirm('Deseja realmente excluir?')" class="btn btn-danger">Excluir todos os próximos eventos</a>
                                                <?php
                                            }
                                            ?>
                                        </form>
                                    </div>
                                </div>
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