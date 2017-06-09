<?php
$admin = true;
include './function/valida_login.php';
include './function/connection.php';
include './function/enviar_email.php';

$id = intval($_GET['id']);
if (!$_SESSION["usuario"]["admin"] && $id != $_SESSION["usuario"]["id"]) {
    header("Location: index.php");
    exit();
}

if (isset($_POST['desmatricular'])) {
    //Desativar matricula do usuario no curso
    $req = $conn->prepare('UPDATE cursos_usuarios SET status = 0 WHERE usuario_id = :usuario_id AND curso_id = :curso_id');
    $req->bindValue(':curso_id', $_POST['desmatricular'], PDO::PARAM_INT);
    $req->bindValue(':usuario_id', $id, PDO::PARAM_INT);
    $req->execute();

    //Remover pagamento
    $pay = $conn->prepare('DELETE FROM pagamentos WHERE curso_id = :curso_id AND usuario_id = :usuario_id AND status = 0');
    $pay->bindValue(':curso_id', $_POST['desmatricular'], PDO::PARAM_INT);
    $pay->bindValue(':usuario_id', $id, PDO::PARAM_INT);
    $pay->execute();

    //Pegar email do usuario
    $reqUsuario = $conn->prepare('SELECT * FROM usuarios WHERE id = :usuario_id');
    $reqUsuario->execute(array('usuario_id' => $id));
    $rowUsuario = $reqUsuario->fetch();

    //Pegar nome do curso
    $reqCurso = $conn->prepare('SELECT * FROM cursos WHERE id = :curso_id');
    $reqCurso->execute(array('curso_id' => $_POST['desmatricular']));
    $rowCurso = $reqCurso->fetch();

    //enviar email
    $assunto = "Desmatrícula";
    $destinatarios = array($rowUsuario["email"]);
    $texto = "Você foi desmatriculado!";
    $texto .= "\n\nCurso:";
    $texto .= "\n- " . utf8_encode($rowCurso["nome"]);
    $texto .= "\n\nData: " . date("d-m-Y H:i:s");
    enviar_email($assunto, $destinatarios, $texto, true);

    header("Location: usuarios_cursos.php?id=" . $id);
    exit();
}

if (isset($_POST['editar'])) {
    //Pegar a lista de cursos selecionados
    $cursos_lista = $_POST['cursos'];

    //Definir vencimento
    if (date('d') > 10) {
        if (date('m') < 12) {
            $vencimento = date('Y') . '-' . str_pad((date('m') + 1), 2, "0", STR_PAD_LEFT) . '-10';
        } else {
            $vencimento = (date('Y') + 1) . '-01-10';
        }
    } else {
        $vencimento = date('Y') . '-' . date('m') . '-10';
    }

    //Inserir os cursos selecionados
    foreach ($cursos_lista as $curso) {
        //Vincular curso
        $insert = $conn->prepare("INSERT INTO cursos_usuarios (usuario_id, curso_id, status) VALUES (:usuario_id, :curso_id, 1) ON DUPLICATE KEY UPDATE status = 1");
        $insert->bindValue(':usuario_id', $id, PDO::PARAM_INT);
        $insert->bindValue(':curso_id', $curso, PDO::PARAM_INT);
        $insert->execute();

        //Criar pagamento
        $pay = $conn->prepare("INSERT INTO pagamentos (usuario_id, curso_id, status, vencimento) VALUES (:usuario_id, :curso_id, 0, :vencimento)");
        $pay->bindValue(':usuario_id', $id, PDO::PARAM_INT);
        $pay->bindValue(':curso_id', $curso, PDO::PARAM_INT);
        $pay->bindValue(':vencimento', $vencimento, PDO::PARAM_STR);
        $pay->execute();
    }

    //Pegar email do usuario
    $reqUsuario = $conn->prepare('SELECT * FROM usuarios WHERE id = :usuario_id');
    $reqUsuario->execute(array('usuario_id' => $usuario_id));
    $rowUsuario = $reqUsuario->fetch();

    //enviar email
    $assunto = "Matrícula";
    $destinatarios = array($rowUsuario["email"]);
    $texto = "Você está inscrito!";
    $texto .= "\n\nCurso(s):";
    foreach ($cursos_lista as $curso) {
        //Pegar nome do curso
        $reqCurso = $conn->prepare('SELECT * FROM cursos WHERE id = :curso_id');
        $reqCurso->execute(array('curso_id' => $curso));
        $rowCurso = $reqCurso->fetch();

        $texto .= "\n- " . utf8_encode($rowCurso["nome"]);
    }
    $texto .= "\n\nData: " . date("d-m-Y H:i:s");
    enviar_email($assunto, $destinatarios, $texto, true);

    header("Location: usuarios_cursos.php?id=" . $id);
    exit();
}
$req = $conn->query('SELECT * FROM cursos WHERE vinculo = 1');

//Pegar os cursos salvos deste usuario
$req2 = $conn->prepare('SELECT * FROM cursos_usuarios IU, cursos I WHERE IU.curso_id = I.id AND IU.usuario_id = :id AND IU.status = 1');
$req2->bindValue(':id', $id, PDO::PARAM_INT);
$req2->execute();

//Criar um array com os ids dos cursos salvos
$cursos_usuarios = '';
while ($linha = $req2->fetch(PDO::FETCH_ASSOC)) {
    $cursos_usuarios .= $linha["curso_id"] . ",";
}
$cursos_usuarios = rtrim($cursos_usuarios, ",");
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Vincular cursos - MusicSys</title>
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
                        <h1 class="page-header">Vincular cursos</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Vincular cursos
                                <a href="usuarios_listar.php" class="btn btn-info btn-panel-heading">Voltar</a>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <?php
                                        if ($linha["ativo"]) {
                                            ?>
                                            <form role="form" method="post">
                                                <div class="form-group">
                                                    <?php
                                                    while ($linha = $req->fetch(PDO::FETCH_ASSOC)) {
                                                        //Verificar se curso ja esta adicionado ao usuario
                                                        $checkCurso = strpos($cursos_usuarios, $linha["id"]) === false ? false : true;

                                                        echo '<div class="checkbox"><label>';
                                                        echo '<input type="checkbox" value="' . $linha["id"] . '" name="cursos[]" ' . ($checkCurso === true ? " checked disabled" : "" ) . '>';
                                                        echo utf8_encode($linha["nome"]);
                                                        echo '</label>';

                                                        //Mostrar botao de Desmatricular se o curso ja tiver sido adicionado e o usuario for admin
                                                        if ($checkCurso && $_SESSION["usuario"]["admin"]) {
                                                            echo '<button type="submit" name="desmatricular" value="' . $linha["id"] . '" class="btn btn-xs btn-danger margin-left-sm">Desmatricular</button>';
                                                        }
                                                        echo '</div>';
                                                    }
                                                    ?>
                                                </div>
                                                <button type="submit" name="editar" class="btn btn-primary">Salvar</button>
                                            </form>
                                            <?php
                                        } else {
                                            echo '<p class="form-control-static">O usuário está INATIVO!</p>';
                                        }
                                        ?>
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