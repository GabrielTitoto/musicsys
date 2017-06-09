<?php
$admin = true;
include './function/valida_login.php';
include './function/connection.php';
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
                        <h1 class="page-header">Cursos - Listar</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Cursos
                                <a href="cursos_inserir.php" class="btn btn-success btn-panel-heading">Criar curso</a>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome</th>
                                                <th>Valor</th>
                                                <th>Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $req = $conn->query('SELECT * FROM cursos WHERE vinculo = 1');
                                            if ($req) {
                                                while ($linha = $req->fetch(PDO::FETCH_ASSOC)) {
                                                    echo "<tr>";
                                                    echo "<td>{$linha['id']}</td>";
                                                    echo "<td>" . utf8_encode($linha['nome']) . "</td>";
                                                    echo "<td>" . number_format($linha['valor'], 2, ',', '.') . "</td>";
                                                    echo "<td>";
                                                    echo "<a href='cursos_visualizar.php?id={$linha['id']}' class='btn btn-info btn-circle' title='Visualizar Curso'><i class='fa fa-eye'></i></a> ";
                                                    echo "<a href='cursos_editar.php?id={$linha['id']}' class='btn btn-warning btn-circle' title='Editar Curso'><i class='fa fa-pencil'></i></a> ";
                                                    echo "<a href='cursos_deletar.php?id={$linha['id']}' onclick='return confirm(\"Deseja realmente excluir?\")' class='btn btn-danger btn-circle' title='Deletar Curso'><i class='fa fa-trash'></i></a>";
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