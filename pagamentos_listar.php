<?php
$admin = false;
include './function/valida_login.php';
include './function/connection.php';
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
                        <h1 class="page-header">Pagamentos - Listar</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Pagamentos
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Aluno</th>
                                                <th>Curso</th>
                                                <th>Transação</th>
                                                <th>Status</th>
                                                <th>Vencimento</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            if ($_SESSION["usuario"]["admin"]) {
                                                $req = $conn->query('SELECT P.id, P.usuario_id, U.nome as usuario, I.nome as curso, P.transacao, S.id as status, S.significado, P.vencimento, P.criado FROM pagamentos P, usuarios U, cursos I, status_pagamento S WHERE P.usuario_id = U.id AND P.curso_id = I.id AND P.status = S.id ORDER BY vencimento DESC, usuario ASC');
                                            } else {
                                                $req = $conn->query('SELECT P.id, P.usuario_id, U.nome as usuario, I.nome as curso, P.transacao, S.id as status, S.significado, P.vencimento, P.criado FROM pagamentos P, usuarios U, cursos I, status_pagamento S WHERE P.usuario_id = U.id AND P.curso_id = I.id AND P.status = S.id AND P.usuario_id = ' . $_SESSION["usuario"]["id"] . ' ORDER BY vencimento DESC, usuario ASC');
                                            }

                                            if ($req) {
                                                while ($linha = $req->fetch(PDO::FETCH_ASSOC)) {
                                                    $vencimento = strtotime($linha['vencimento']) - strtotime(date('Y-m-d'));
                                                    $vencimento = floor($vencimento / (60 * 60 * 24));

                                                    $vencido = "";
                                                    if ($vencimento < 0 && $linha['status'] != 3 && $linha['status'] != 4) {
                                                        $vencido = "text-danger";
                                                    }

                                                    echo "<tr class='{$vencido}'>";
                                                    echo "<td>{$linha['id']}</td>";
                                                    echo "<td>{$linha['usuario']}</td>";
                                                    echo "<td>" . utf8_encode($linha['curso']) . "</td>";
                                                    echo "<td>{$linha['transacao']}</td>";
                                                    echo "<td>" . utf8_encode($linha['significado']) . "</td>";
                                                    echo "<td>{$linha['vencimento']}</td>";
                                                    echo "<td>";
                                                    echo "<a href='pagamentos_visualizar.php?id={$linha['id']}' class='btn btn-info btn-circle' title='Visualizar Material'><i class='fa fa-eye'></i></a> ";
                                                    if ($_SESSION["usuario"]["admin"] && $linha['status'] != 3 && $linha['status'] != 4) {
                                                        echo "<a href='pagamentos_editar.php?id={$linha['id']}' class='btn btn-warning btn-circle' title='Editar Pagamento'><i class='fa fa-pencil'></i></a> ";
                                                    } elseif ($linha['status'] != 3 && $linha['status'] != 4) {
                                                        echo "<a href='pagamentos_pagar.php?id={$linha['id']}' class='btn btn-success btn-circle' title='Pagar'><i class='fa fa-credit-card'></i></a>";
                                                    }
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