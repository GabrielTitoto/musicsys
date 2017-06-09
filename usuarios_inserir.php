<?php
$admin = true;
include './function/valida_login.php';
include './function/connection.php';

if (isset($_POST['cadastrar'])) {
    $nome = utf8_decode($_POST['nome']);
    $email = $_POST['email'];
    $senha = sha1($_POST['senha']);
    $endereco = utf8_decode($_POST['endereco']);
    $rg = $_POST['rg'];
    $cpf = $_POST['cpf'];
    $admin = $_POST['admin'];
    $ativo = $_POST['ativo'];
    $telefone = $_POST['telefone'];
    $celular = $_POST['celular'];

    $req = $conn->prepare('INSERT INTO usuarios (nome, email, senha, endereco, rg, cpf, admin, ativo) VALUES (:nome, :email, :senha, :endereco, :rg, :cpf, :admin, :ativo)');
    $req->bindValue(':nome', $nome, PDO::PARAM_STR);
    $req->bindValue(':email', $email, PDO::PARAM_STR);
    $req->bindValue(':senha', $senha, PDO::PARAM_STR);
    $req->bindValue(':endereco', $endereco, PDO::PARAM_STR);
    $req->bindValue(':rg', $rg, PDO::PARAM_STR);
    $req->bindValue(':cpf', $cpf, PDO::PARAM_STR);
    $req->bindValue(':admin', $admin, PDO::PARAM_INT);
    $req->bindValue(':ativo', $ativo, PDO::PARAM_INT);
    $req->execute();
    $id_usuario = $conn->lastInsertId();

    $req2 = $conn->prepare('INSERT INTO telefones (usuario_id, tipo, numero) VALUES (:usuario_id, :tipo, :numero)');
    $req2->bindValue(':usuario_id', $id_usuario, PDO::PARAM_STR);
    $req2->bindValue(':tipo', 'telefone', PDO::PARAM_STR);
    $req2->bindValue(':numero', $telefone, PDO::PARAM_STR);
    $req2->execute();
    $req2->bindValue(':usuario_id', $id_usuario, PDO::PARAM_STR);
    $req2->bindValue(':tipo', 'celular', PDO::PARAM_STR);
    $req2->bindValue(':numero', $celular, PDO::PARAM_STR);
    $req2->execute();

    header("Location: usuarios_listar.php");
}
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
                        <h1 class="page-header">Alunos - Inserir</h1>
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
                                                <label>Nome</label>
                                                <input class="form-control" type="text" name="nome" placeholder="Digite o nome" required>
                                            </div>
                                            <div class="form-group">
                                                <label>E-mail</label>
                                                <input class="form-control" type="email" name="email" placeholder="Digite o email" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Senha</label>
                                                <input class="form-control" type="password" name="senha" placeholder="Digite a senha" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Endereço</label>
                                                <input class="form-control" placeholder="Endereço" name="endereco" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label>Telefone</label>
                                                <input class="form-control" placeholder="Telefone" name="telefone" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label>Celular</label>
                                                <input class="form-control" placeholder="Celular" name="celular" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label>RG</label>
                                                <input class="form-control" placeholder="RG" name="rg" type="text">
                                            </div>
                                            <div class="form-group">
                                                <label>CPF</label>
                                                <input class="form-control" placeholder="CPF" name="cpf" type="text">
                                            </div>
                                            <?php if ($_SESSION["usuario"]["admin"]) { ?>
                                                <div class="form-group">
                                                    <label>Administrador</label>
                                                    <select class="form-control" name="admin">
                                                        <option value="0">Não</option>
                                                        <option value="1">Sim</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Ativo</label>
                                                    <select class="form-control" name="ativo">
                                                        <option value="1">Sim</option>
                                                        <option value="0">Não</option>
                                                    </select>
                                                </div>
                                            <?php } ?>
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