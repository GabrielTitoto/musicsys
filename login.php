<?php
session_start();
$_SESSION["usuario"]["logado"] = false;

if (isset($_POST['login'])) {

    include './function/connection.php';

    $email = $_POST['email'];
    $senha = sha1($_POST['senha']);

    $req = $conn->prepare('SELECT * FROM usuarios WHERE email = :email AND senha = :senha AND ativo = 1');
    $req->bindValue(':email', $email, PDO::PARAM_STR);
    $req->bindValue(':senha', $senha, PDO::PARAM_STR);
    $req->execute();
    $linha = $req->fetch(PDO::FETCH_ASSOC);
    if ($req->rowCount() > 0) {
        $_SESSION["usuario"]["logado"] = true;
        $_SESSION["usuario"]["id"] = $linha['id'];
        $_SESSION["usuario"]["nome"] = $linha['nome'];
        $_SESSION["usuario"]["email"] = $linha['email'];
        $_SESSION["usuario"]["admin"] = $linha['admin'];
        header("Location: index.php");
        exit();
    } else {
        $erro = true;
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - MusicSys</title>
        <link href="css/bootstrap.min.css" rel="stylesheet">
        <link href="css/metisMenu.min.css" rel="stylesheet">
        <link href="css/sb-admin-2.css" rel="stylesheet">
        <link href="css/estilo.css" rel="stylesheet">
        <link href="css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
            <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
    </head>

    <body>

        <div class="container">
            <?php
            if (isset($erro) && $erro) {
                echo "<div class='alert alert-danger alert-dismissable'>" .
                "<button type = 'button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>" .
                "Não foi possível efetuar o login." .
                "</div>";
            }
            ?>
            <div class="row">
                <div class="col-md-4 col-md-offset-4">
                    <div class="logo">
                        <h1>MusicSys</h1>
                    </div>
                    <div class="login-panel panel panel-default">
                        <div class="panel-heading">
                            <h3 class="panel-title">Entrar</h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="E-mail" name="email" type="email" required autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Senha" name="senha" type="password" required>
                                    </div>
                                    <input type="submit" name="login" class="btn btn-lg btn-success btn-block" value="Entrar">
                                </fieldset>
                            </form>
                        </div>
                        <div class="panel-footer text-right">
                            <a href="usuarios_cadastro.php">Cadastrar</a>
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