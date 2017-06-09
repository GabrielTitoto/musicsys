<?php
$admin = true;
include './function/valida_login.php';
include './function/connection.php';

$id = intval($_GET['id']);

if (isset($_POST['editar'])) {
    $nome = utf8_decode($_POST['nome']);
    $valor = $_POST['valor'];

    $req = $conn->prepare('UPDATE cursos SET nome = :nome, valor = :valor WHERE id = :id');
    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->bindValue(':nome', $nome, PDO::PARAM_STR);
    $req->bindValue(':valor', $valor, PDO::PARAM_STR);
    $req->execute();

    header("Location: cursos_listar.php");
}
$id = intval($id);
$req = $conn->prepare('SELECT * FROM cursos WHERE id = :id');
$req->execute(array('id' => $id));
$linha = $req->fetch(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Cursos - MusicSys</title>
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
                        <h1 class="page-header">Cursos - Editar</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Cursos
                                <a href="cursos_listar.php" class="btn btn-info btn-panel-heading">Voltar</a>
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
                                                <input class="form-control" type="text" name="nome" placeholder="Digite o nome" value="<?php echo utf8_encode($linha['nome']); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Valor</label>
                                                <input class="form-control" type="number" step="0.01" name="valor" placeholder="Digite o valor" value="<?php echo $linha['valor']; ?>">
                                            </div>
                                            <button type="submit" name="editar" class="btn btn-primary">Salvar</button>
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