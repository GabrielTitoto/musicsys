<?php
$admin = true;
include './function/valida_login.php';
include './function/connection.php';

$id = intval($_GET['id']);

if (isset($_POST['editar'])) {
    $vencimento = $_POST["vencimento"];
    $status = $_POST["status"];
    $local = utf8_decode($_POST["local"]);
    $usuario_id = $_POST["usuario_id"];
    $curso_id = $_POST["curso_id"];

    $req = $conn->prepare('UPDATE pagamentos SET vencimento = :vencimento, status = :status WHERE id = :id');
    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->bindValue(':vencimento', $vencimento, PDO::PARAM_STR);
    $req->bindValue(':status', $status, PDO::PARAM_INT);
    $req->execute();

    if ($status == 3 || $status == 4) {
        //Criar pagamento
        $pay = $conn->prepare("INSERT INTO pagamentos (usuario_id, curso_id, status, vencimento) VALUES (:usuario_id, :curso_id, 0, :vencimento)");
        $pay->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
        $pay->bindValue(':curso_id', $curso_id, PDO::PARAM_INT);
        $pay->bindValue(':vencimento', date('Y-m-d', strtotime("+1 month", strtotime($vencimento))), PDO::PARAM_STR);
        $pay->execute();
    }

    header("Location: pagamentos_listar.php");
    exit();
}

$req = $conn->prepare('SELECT P.id, U.id as usuario_id, U.nome as usuario, U.email, I.id as curso_id, I.nome as curso, P.status, S.significado, S.descricao, P.transacao, P.vencimento FROM pagamentos P, usuarios U, cursos I, status_pagamento S WHERE P.id = :id AND P.usuario_id = U.id AND P.curso_id = I.id AND P.status = S.id');
$req->execute(array('id' => $id));
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
                        <h1 class="page-header">Pagamentos - Editar</h1>
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
                                                <input type="hidden" name="usuario_id" value="<?php echo $linha['usuario_id']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>E-mail</label>
                                                <p class="form-control-static"><?php echo $linha['email']; ?></p>
                                            </div>
                                            <div class="form-group">
                                                <label>Curso</label>
                                                <p class="form-control-static"><?php echo utf8_encode($linha['curso']); ?></p>
                                                <input type="hidden" name="curso_id" value="<?php echo $linha['curso_id']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Transação</label>
                                                <?php
                                                if ($linha['status'] == 3 || $linha['status'] == 4) {
                                                    echo "<p class='form-control-static'>" . utf8_encode($linha['transacao']) . "</p>";
                                                } else {
                                                    echo "<input class='form-control' type='text' name='transacao' placeholder='Digite a transação' value='" . $linha['transacao'] . "'>";
                                                }
                                                ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Vencimento</label>
                                                <?php
                                                if ($linha['status'] == 3 || $linha['status'] == 4) {
                                                    echo "<p class='form-control-static'>" . utf8_encode($linha['vencimento']) . "</p>";
                                                } else {
                                                    echo "<input class='form-control' type='date' name='vencimento' placeholder='Digite a data' value='" . $linha['vencimento'] . "' required>";
                                                }
                                                ?>
                                            </div>
                                            <div class="form-group">
                                                <label>Status</label>
                                                <?php
                                                if ($linha['status'] == 3 || $linha['status'] == 4) {
                                                    echo "<p class='form-control-static'>" . utf8_encode($linha['significado']) . "</p>";
                                                } else {
                                                    ?>
                                                    <select class="form-control" name="status" required>
                                                        <option value="">Selecione...</option>
                                                        <?php
                                                        $reqStatus = $conn->query('SELECT * FROM status_pagamento');
                                                        if ($reqStatus) {
                                                            while ($linhaStatus = $reqStatus->fetch(PDO::FETCH_ASSOC)) {
                                                                $selected = ($linha['significado'] == $linhaStatus['significado']) ? 'selected' : '';
                                                                echo "<option value='" . $linhaStatus['id'] . "' " . $selected . ">" . utf8_encode($linhaStatus['significado']) . "</option>";
                                                            }
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php
                                                }
                                                ?>
                                            </div>
                                            <?php
                                            if ($linha['status'] != 3 && $linha['status'] != 4) {
                                                echo "<button type='submit' name='editar' class='btn btn-primary'>Salvar</button>";
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