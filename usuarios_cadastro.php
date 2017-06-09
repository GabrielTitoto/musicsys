<?php
session_start();
$_SESSION["usuario"]["logado"] = false;

if (isset($_POST['cadastrar'])) {

    include './function/connection.php';
    include './function/enviar_email.php';

    $nome = utf8_decode($_POST['nome']);
    $email = $_POST['email'];
    $senha = sha1($_POST['senha']);

    //verifica se email ja existe
    $req = $conn->prepare('SELECT * FROM usuarios WHERE email = :email');
    $req->bindValue(':email', $email, PDO::PARAM_STR);
    $req->execute();
    if ($req->rowCount() == 0) {
        //cadastra usuario
        $req = $conn->prepare('INSERT INTO usuarios (nome, email, senha, ativo) VALUES (:nome, :email, :senha, 1)');
        $req->bindValue(':nome', $nome, PDO::PARAM_STR);
        $req->bindValue(':email', $email, PDO::PARAM_STR);
        $req->bindValue(':senha', $senha, PDO::PARAM_STR);
        $req->execute();

        //enviar email
        $assunto = "Cadastro de usuário";
        $destinatarios = array($email);
        $texto = "Usuário cadastrado com sucesso!";
        $texto .= "\n\nNome: " . $nome;
        $texto .= "\nEmail: " . $email;
        $texto .= "\nData: " . date("d-m-Y H:i:s");
        $texto .= "\n\nPor favor, entre em contato com o professor para marcar suas aulas (11) 9999-9999.";
        enviar_email($assunto, $destinatarios, $texto, true);

        header("Location: login.php");
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
        <title>Cadastro - MusicSys</title>
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
                "E-mail já cadastrado, faça o login." .
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
                            <h3 class="panel-title">Cadastrar</h3>
                        </div>
                        <div class="panel-body">
                            <form role="form" method="post">
                                <fieldset>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Nome" name="nome" type="text" required autofocus>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="E-mail" name="email" type="email" required>
                                    </div>
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Senha" name="senha" type="password" required>
                                    </div>
                                    <input type="submit" name="cadastrar" class="btn btn-lg btn-success btn-block" value="Cadastrar">
                                </fieldset>
                            </form>
                        </div>
                        <div class="panel-footer text-right">
                            <a href="login.php">Entrar</a>
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