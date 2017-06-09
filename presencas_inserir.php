<?php
$admin = true;
include './function/valida_login.php';
include './function/connection.php';

if (isset($_POST['cadastrar'])) {
    $usuario_id = $_POST['usuario_id'];
    $data = $_POST['data'];
    $hora = $_POST['hora'];
    $presenca = $_POST['presenca'];

    $req = $conn->prepare('INSERT INTO presencas (usuario_id, data, hora, presenca) VALUES (:usuario_id, :data, :hora, :presenca)');
    $req->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $req->bindValue(':data', $data, PDO::PARAM_STR);
    $req->bindValue(':hora', $hora, PDO::PARAM_STR);
    $req->bindValue(':presenca', $presenca, PDO::PARAM_BOOL);
    $req->execute();

    header("Location: presencas_listar.php");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Presenças - MusicSys</title>
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
                        <h1 class="page-header">Presenças - Inserir</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Presenças
                                <a href="presencas_listar.php" class="btn btn-info btn-panel-heading">Voltar</a>
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
                                                <label>Data</label>
                                                <input class="form-control" type="date" name="data" value="<?php echo date("Y-m-d") ?>" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Hora</label>
                                                <select class="form-control" name="hora" required>
                                                    <option>Selecione...</option>
                                                    <option value="8:00">8:00</option>
                                                    <option value="9:00">9:00</option>
                                                    <option value="10:00">10:00</option>
                                                    <option value="13:00">13:00</option>
                                                    <option value="14:00">14:00</option>
                                                    <option value="15:00">15:00</option>
                                                    <option value="16:00">16:00</option>
                                                    <option value="17:00">17:00</option>
                                                    <option value="18:00">18:00</option>
                                                    <option value="19:00">19:00</option>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Presença</label>
                                                <select class="form-control" name="presenca">
                                                    <option value="1">Sim</option>
                                                    <option value="0">Não</option>
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