<?php
$admin = false;
include './function/valida_login.php';
include './function/connection.php';

$id = $_GET['id'];
$id = intval($id);
$req = $conn->prepare('SELECT * FROM usuarios WHERE id = :id');
$req->execute(array('id' => $id));
$linha = $req->fetch(PDO::FETCH_ASSOC);

$req2 = $conn->prepare('SELECT * FROM telefones WHERE usuario_id = :usuario_id AND tipo = "telefone"');
$req2->execute(array('usuario_id' => $id));
$telefone = $req2->fetch(PDO::FETCH_ASSOC);

$req3 = $conn->prepare('SELECT * FROM telefones WHERE usuario_id = :usuario_id AND tipo = "celular"');
$req3->execute(array('usuario_id' => $id));
$celular = $req3->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Gerenciar alunos - MusicSys</title>
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
                        <h1 class="page-header">Alunos - Visualizar</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Alunos
                                <a href="usuarios_listar.php" class="btn btn-info btn-panel-heading">Voltar</a>
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
                                                <label>Nome</label>
                                                <p class="form-control-static"><?php echo utf8_encode($linha['nome']); ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label>E-mail</label>
                                                <p class="form-control-static"><?php echo $linha['email']; ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label>Endereço</label>
                                                <p class="form-control-static"><?php echo utf8_encode($linha['endereco']); ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label>Telefone</label>
                                                <p class="form-control-static"><?php echo $telefone['numero']; ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label>Celular</label>
                                                <p class="form-control-static"><?php echo $celular['numero']; ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label>RG</label>
                                                <p class="form-control-static"><?php echo $linha['rg']; ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label>CPF</label>
                                                <p class="form-control-static"><?php echo $linha['cpf']; ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label>Administrador</label>
                                                <p class="form-control-static"><?php echo $linha['admin'] == 1 ? "Sim" : "Não"; ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label>Ativo</label>
                                                <p class="form-control-static"><?php echo $linha['ativo'] == 1 ? "Sim" : "Não"; ?></p>
                                            </div>
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