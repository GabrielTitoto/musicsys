<?php
$admin = true;
include './function/valida_login.php';
include './function/connection.php';

$usuario_id = 0;
if (isset($_POST['filtrar']) && !empty($_POST['usuario_id'])) {
    $usuario_id = $_POST['usuario_id'];

    $reqPresencas = $conn->prepare('SELECT P.id, U.nome, P.data, P.hora, P.presenca FROM presencas P, usuarios U where P.usuario_id = U.id AND U.id = :usuario_id ORDER BY data DESC');
    $reqPresencas->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $reqPresencas->execute();
} else {
    $reqPresencas = $conn->query('SELECT P.id, U.nome, P.data, P.hora, P.presenca FROM presencas P, usuarios U where P.usuario_id = U.id ORDER BY data DESC');
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
                        <h1 class="page-header">Presenças - Listar</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <a href="presencas_inserir.php" class="btn btn-success btn-panel-heading">Inserir presença</a>
                                <form class="form-inline" method="post">
                                    <label>Filtrar por usuário: </label>
                                    <div class="form-group">
                                        <select class="form-control" name="usuario_id">
                                            <option value="">Selecione...</option>
                                            <?php
                                            $req = $conn->query('SELECT * FROM usuarios WHERE ativo = 1 ORDER BY nome ASC');
                                            if ($req) {
                                                while ($linha = $req->fetch(PDO::FETCH_ASSOC)) {
                                                    echo "<option  value='" . $linha['id'] . "' " . ($linha["id"] === $usuario_id ? " selected" : "" ) . ">" . $linha['id'] . " - " . utf8_encode($linha['nome']) . "</option>";
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                    <button type="submit" name="filtrar" class="btn btn-primary">Filtrar</button>
                                </form>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Aluno</th>                                                
                                                <th>Data</th>
                                                <th>Hora</th>
                                                <th>Presença</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($reqPresencas) {
                                                while ($linha = $reqPresencas->fetch(PDO::FETCH_ASSOC)) {
                                                    echo "<tr>";
                                                    echo "<td>{$linha['id']}</td>";
                                                    echo "<td>" . utf8_encode($linha['nome']) . "</td>";
                                                    echo "<td>{$linha['data']}</td>";
                                                    echo "<td>{$linha['hora']}</td>";
                                                    echo "<td>" . ($linha['presenca'] == 1 ? "Sim" : "Não") . "</td>";
                                                    echo "<td>";
                                                    echo "<a href='presencas_deletar.php?id={$linha['id']}' onclick='return confirm(\"Deseja realmente excluir?\")' class='btn btn-danger btn-circle' title='Deletar Presença'><i class='fa fa-trash'></i></a>";
                                                    echo "</td>";
                                                    echo "</tr>";
                                                }
                                            }
                                            ?>
                                        </tbody>
                                    </table>
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