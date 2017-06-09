<?php
$admin = false;
include './function/valida_login.php';
include './function/connection.php';

//Pegar total de usuarios
$reqUsuarios = $conn->prepare('SELECT * FROM usuarios');
$reqUsuarios->execute();
$total_usuarios = $reqUsuarios->rowCount();

//Pegar total de cursos
$reqCursos = $conn->prepare('SELECT * FROM cursos');
$reqCursos->execute();
$total_cursos = $reqCursos->rowCount();

//Pegar total dos meus cursos
$reqMeusCursos = $conn->prepare('SELECT * FROM cursos_usuarios WHERE usuario_id = :usuario_id');
$reqMeusCursos->execute(array('usuario_id' => $_SESSION["usuario"]["id"]));
$total_meus_cursos = $reqMeusCursos->rowCount();

//Pegar total de materias
if ($_SESSION["usuario"]["admin"]) {
    $reqMateriais = $conn->query('SELECT * FROM materiais');
    $reqMateriais->execute();
} else {
    $reqMateriais = $conn->prepare('SELECT * FROM materiais WHERE usuario_id = :usuario_id');
    $reqMateriais->execute(array('usuario_id' => $_SESSION["usuario"]["id"]));
}
$total_materiais = $reqMateriais->rowCount();

//Pegar total de presencas confirmadas
$reqPresencas = $conn->prepare("SELECT * FROM presencas where presenca = 1");
$reqPresencas->execute();
$total_presencas = $reqPresencas->rowCount();
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>MusicSys</title>
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
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-12">
                            <h1 class="page-header">Início</h1>
                        </div>
                    </div>
                    <div class="row">
                        <?php if ($_SESSION["usuario"]["admin"]) { ?>
                            <div class="col-lg-4 col-md-6">
                                <div class="panel panel-blue">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-user fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?php echo $total_usuarios; ?></div>
                                                <div>Gerenciar alunos</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="usuarios_listar.php">
                                        <div class="panel-footer">
                                            <span class="pull-left">Ver mais</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="panel panel-yellow">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-music fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?php echo $total_cursos; ?></div>
                                                <div>Cadastrar cursos</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="cursos_listar.php">
                                        <div class="panel-footer">
                                            <span class="pull-left">Ver mais</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-6">
                                <div class="panel panel-red">
                                    <div class="panel-heading">
                                        <div class="row">
                                            <div class="col-xs-3">
                                                <i class="fa fa-check-circle-o fa-5x"></i>
                                            </div>
                                            <div class="col-xs-9 text-right">
                                                <div class="huge"><?php echo $total_presencas; ?></div>
                                                <div>Lista de presença</div>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="presencas_listar.php">
                                        <div class="panel-footer">
                                            <span class="pull-left">Ver mais</span>
                                            <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                            <div class="clearfix"></div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col-lg-4 col-md-6">
                            <div class="panel panel-green">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-file-o fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"><?php echo $total_materiais; ?></div>
                                            <div>Materiais</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="materiais_listar.php">
                                    <div class="panel-footer">
                                        <span class="pull-left">Ver mais</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="panel panel-bluelight">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-music fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"></div>
                                            <div>Pagamentos</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="pagamentos_listar.php">
                                    <div class="panel-footer">
                                        <span class="pull-left">Ver mais</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-6">
                            <div class="panel panel-purple">
                                <div class="panel-heading">
                                    <div class="row">
                                        <div class="col-xs-3">
                                            <i class="fa fa-music fa-5x"></i>
                                        </div>
                                        <div class="col-xs-9 text-right">
                                            <div class="huge"></div>
                                            <div>Horários</div>
                                        </div>
                                    </div>
                                </div>
                                <a href="horarios_listar.php">
                                    <div class="panel-footer">
                                        <span class="pull-left">Ver mais</span>
                                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                                        <div class="clearfix"></div>
                                    </div>
                                </a>
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