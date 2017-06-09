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
                        <h1 class="page-header">Alunos - Listar</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Alunos
                                <a href="usuarios_inserir.php" class="btn btn-success btn-panel-heading">Criar aluno</a>
                            </div>
                            <div class="panel-body">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Nome</th>
                                                <th>E-mail</th>
                                                <th>Administrador</th>
                                                <th>Ativo</th>
                                                <th style="text-align:right">Ações</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $req = $conn->query('SELECT * FROM usuarios');
                                            if ($req) {
                                                while ($linha = $req->fetch(PDO::FETCH_ASSOC)) {
                                                    echo "<tr>";
                                                    echo "<td>{$linha['id']}</td>";
                                                    echo "<td>" . utf8_encode($linha['nome']) . "</td>";
                                                    echo "<td>{$linha['email']}</td>";
                                                    echo "<td>" . ($linha['admin'] == 1 ? "Sim" : "Não") . "</td>";
                                                    echo "<td>" . ($linha['ativo'] == 1 ? "Sim" : "Não") . "</td>";
                                                    echo "<td align='right'>";
                                                    if ($linha['ativo']) {
                                                        echo "<a href='usuarios_cursos.php?id={$linha['id']}' class='btn btn-primary btn-circle' title='Vincular cursos'><i class='fa fa-link'></i></a> ";
                                                    }
                                                    echo "<a href='usuarios_visualizar.php?id={$linha['id']}' class='btn btn-info btn-circle' title='Visualizar Usuário'><i class='fa fa-eye'></i></a> ";
                                                    echo "<a href='usuarios_editar.php?id={$linha['id']}' class='btn btn-warning btn-circle' title='Editar Usuário'><i class='fa fa-pencil'></i></a> ";
                                                    echo "<a href='usuarios_deletar.php?id={$linha['id']}' onclick='return confirm(\"Deseja realmente excluir?\")' class='btn btn-danger btn-circle' title='Deletar Usuário'><i class='fa fa-trash'></i></a>";
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