<?php
$admin = true;
include './function/valida_login.php';
include './function/connection.php';

if (isset($_POST['cadastrar'])) {
    $usuario_id = $_POST["usuario_id"];
    $curso_id = $_POST["curso_id"];
    $data = $_POST["data"];
    $hora = $_POST["hora"];
    $local = utf8_decode($_POST["local"]);
    $repetir = $_POST["repetir"];

    for ($i = 0; $i < $repetir; $i++) {
        $req = $conn->prepare('INSERT INTO horarios (usuario_id, curso_id, data, hora, local) VALUES (:usuario_id, :curso_id, :data, :hora, :local)');
        $req->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $req->bindValue(':curso_id', $curso_id, PDO::PARAM_INT);
        $req->bindValue(':data', $data, PDO::PARAM_STR);
        $req->bindValue(':hora', $hora, PDO::PARAM_STR);
        $req->bindValue(':local', $local, PDO::PARAM_STR);
        $exec = $req->execute();

        $data = date('Y-m-d', strtotime($data . ' +7 days'));
    }

    header("Location: horarios_listar.php");
}
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
                        <h1 class="page-header">Horários - Inserir</h1>
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
                                                <label>Aluno</label>
                                                <select class="form-control" name="usuario_id" required>
                                                    <option value="">Selecione...</option>
                                                    <?php
                                                    $req = $conn->query('SELECT * FROM usuarios WHERE ativo = 1 ORDER BY nome ASC');
                                                    if ($req) {
                                                        while ($linha = $req->fetch(PDO::FETCH_ASSOC)) {
                                                            echo "<option value=" . $linha['id'] . ">" . $linha['id'] . " - " . utf8_encode($linha['nome']) . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div> 
                                            <div class="form-group">
                                                <label>Curso</label>
                                                <select class="form-control" name="curso_id" required>
                                                    <option value="">Selecione...</option>
                                                    <?php
                                                    $req = $conn->query('SELECT * FROM cursos ORDER BY nome ASC');
                                                    if ($req) {
                                                        while ($linha = $req->fetch(PDO::FETCH_ASSOC)) {
                                                            echo "<option value=" . $linha['id'] . ">" . utf8_encode($linha['nome']) . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">  
                                                <label>Data</label>      
                                                <input class="form-control" type="date" name="data" placeholder="Digite a data" required>
                                            </div>
                                            <div class="form-group">  
                                                <label>Hora</label> 
                                                <input class="form-control" type="time" name="hora" placeholder="Digite o horário" required>
                                            </div>
                                            <div class="form-group">  
                                                <label>Local</label> 
                                                <input class="form-control" type="text" name="local" placeholder="Digite o local" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Repetir</label>
                                                <select class="form-control" name="repetir" required>
                                                    <option value="1" selected>Uma vez</option>
                                                    <option value="4">Um mês</option>
                                                    <option value="24">Seis meses</option>
                                                    <option value="48">Um ano</option>
                                                </select>
                                            </div>
                                            <button type="submit" name="cadastrar" class="btn btn-primary">Salvar</button>
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