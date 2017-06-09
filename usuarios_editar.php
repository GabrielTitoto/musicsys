<?php
$admin = false;
include './function/valida_login.php';
include './function/connection.php';

$id = $_GET['id'];
if (!$_SESSION["usuario"]["admin"] && $id != $_SESSION["usuario"]["id"]) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['editar'])) {
    $nome = utf8_decode($_POST['nome']);
    $email = $_POST['email'];
    $senha = empty($_POST['senha']) ? null : sha1($_POST['senha']);
    $endereco = utf8_decode($_POST['endereco']);
    $rg = $_POST['rg'];
    $cpf = $_POST['cpf'];
    $admin = $_POST['admin'];
    $ativo = $_POST['ativo'];
    $telefone_id = $_POST['telefone_id'];
    $telefone = $_POST['telefone'];
    $celular_id = $_POST['celular_id'];
    $celular = $_POST['celular'];

    $req = $conn->prepare('UPDATE usuarios SET nome = :nome, email = :email, senha = IFNULL(:senha, senha), endereco = :endereco, cpf = :cpf, rg = :rg, admin = :admin, ativo = :ativo WHERE id = :id');
    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->bindValue(':nome', $nome, PDO::PARAM_STR);
    $req->bindValue(':email', $email, PDO::PARAM_STR);
    $req->bindValue(':senha', $senha, PDO::PARAM_STR);
    $req->bindValue(':endereco', $endereco, PDO::PARAM_STR);
    $req->bindValue(':rg', $rg, PDO::PARAM_STR);
    $req->bindValue(':cpf', $cpf, PDO::PARAM_STR);
    $req->bindValue(':admin', $admin, PDO::PARAM_INT);
    $req->bindValue(':ativo', $ativo, PDO::PARAM_INT);
    $req->execute();

    if (isset($telefone_id) && !empty($telefone_id)) {
        $req2 = $conn->prepare('UPDATE telefones SET numero = :telefone WHERE id = :telefone_id');
        $req2->bindValue(':telefone_id', $telefone_id, PDO::PARAM_INT);
        $req2->bindValue(':telefone', $telefone, PDO::PARAM_STR);
        $req2->execute();
    } else {
        $req2 = $conn->prepare('INSERT INTO telefones (usuario_id, tipo, numero) VALUES (:usuario_id, :tipo, :numero)');
        $req2->bindValue(':usuario_id', $id, PDO::PARAM_STR);
        $req2->bindValue(':tipo', 'telefone', PDO::PARAM_STR);
        $req2->bindValue(':numero', $telefone, PDO::PARAM_STR);
        $req2->execute();
    }

    if (isset($celular_id) && !empty($celular_id)) {
        $req3 = $conn->prepare('UPDATE telefones SET numero = :celular WHERE id = :celular_id');
        $req3->bindValue(':celular_id', $celular_id, PDO::PARAM_INT);
        $req3->bindValue(':celular', $celular, PDO::PARAM_STR);
        $req3->execute();
    } else {
        $req2 = $conn->prepare('INSERT INTO telefones (usuario_id, tipo, numero) VALUES (:usuario_id, :tipo, :numero)');
        $req2->bindValue(':usuario_id', $id, PDO::PARAM_STR);
        $req2->bindValue(':tipo', 'celular', PDO::PARAM_STR);
        $req2->bindValue(':numero', $celular, PDO::PARAM_STR);
        $req2->execute();
    }

    header("Location: usuarios_listar.php");
}
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
                        <h1 class="page-header">Alunos - Editar</h1>
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
                                                <input class="form-control" type="text" name="nome" placeholder="Digite o nome" value="<?php echo utf8_encode($linha['nome']); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>E-mail</label>
                                                <input class="form-control" type="email" name="email" placeholder="Digite o email" value="<?php echo $linha['email']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Senha</label>
                                                <input class="form-control" type="password" name="senha" placeholder="Digite a senha">
                                            </div>
                                            <div class="form-group">
                                                <label>Endereço</label>
                                                <input class="form-control" placeholder="Endereço" name="endereco" type="text" value="<?php echo utf8_encode($linha['endereco']); ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Telefone</label>
                                                <input class="form-control" placeholder="Telefone" name="telefone" type="text" value="<?php echo $telefone['numero']; ?>">
                                                <input name="telefone_id" type="hidden" value="<?php echo $telefone['id']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>Celular</label>
                                                <input class="form-control" placeholder="Celular" name="celular" type="text" value="<?php echo $celular['numero']; ?>">
                                                <input name="celular_id" type="hidden" value="<?php echo $celular['id']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>RG</label>
                                                <input class="form-control" placeholder="RG" name="rg" type="text" value="<?php echo $linha['rg']; ?>">
                                            </div>
                                            <div class="form-group">
                                                <label>CPF</label>
                                                <input class="form-control" placeholder="CPF" name="cpf" type="text" value="<?php echo $linha['cpf']; ?>">
                                            </div>
                                            <?php if ($_SESSION["usuario"]["admin"]) { ?>
                                                <div class="form-group">
                                                    <label>Administrador</label>
                                                    <select class="form-control" name="admin">
                                                        <option value="0" <?= ($linha['admin'] == '0') ? 'selected' : '' ?>>Não</option>
                                                        <option value="1" <?= ($linha['admin'] == '1') ? 'selected' : '' ?>>Sim</option>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label>Ativo</label>
                                                    <select class="form-control" name="ativo">
                                                        <option value="1" <?= ($linha['ativo'] == '1') ? 'selected' : '' ?>>Sim</option>
                                                        <option value="0" <?= ($linha['ativo'] == '0') ? 'selected' : '' ?>>Não</option>
                                                    </select>
                                                </div>
                                            <?php } ?>
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