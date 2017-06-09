<?php
$admin = true;
include './function/valida_login.php';
include './function/connection.php';
include './function/enviar_email.php';

if (isset($_POST['cadastrar'])) {
    $usuario_id = $_POST['usuario_id'];
    $nome = utf8_decode($_POST['nome']);
    $descricao = utf8_decode($_POST['descricao']);

    $req = $conn->prepare('INSERT INTO materiais (usuario_id, nome, descricao) VALUES (:usuario_id, :nome, :descricao)');
    $req->bindValue(':usuario_id', $usuario_id, PDO::PARAM_INT);
    $req->bindValue(':nome', $nome, PDO::PARAM_STR);
    $req->bindValue(':descricao', $descricao, PDO::PARAM_STR);
    $req->execute();

    //Pegar id do material
    $lastId = $conn->lastInsertId();

    //Criar pasta de upload dos materiais com o id do material
    mkdir("materiais/" . $lastId, 0777, true);
    $target_path = "materiais/" . $lastId . "/";

    for ($i = 0; $i < count($_FILES['materiais']['name']); $i++) {

        $ext = explode('.', basename($_FILES['materiais']['name'][$i]));
        //Pegar extensao
        $file_extension = end($ext);
        //Gerar nome para imagem
        $target_name = md5($_FILES['materiais']['name'][$i] . "-" . uniqid()) . "." . $file_extension;

        //Verificar tamanho do arquivo
        if (($_FILES['materiais']['size'][$i] < 10485760)) {
            //Mover imagem para pasta
            if (move_uploaded_file($_FILES['materiais']['tmp_name'][$i], $target_path . $target_name)) {
                //echo "Imagem " . ($i + 1) . ": Upload com sucesso!";;
            } else {
                echo "Material " . ($i + 1) . ": Upload com falha!";
            }
        } else {
            echo "Material " . ($i + 1) . ": Tipo ou tamanho incorreto!";
        }
    }

    //Pegar email do usuario
    $reqUsuario = $conn->prepare('SELECT * FROM usuarios WHERE id = :usuario_id');
    $reqUsuario->execute(array('usuario_id' => $usuario_id));
    $rowUsuario = $reqUsuario->fetch();

    //enviar email
    $assunto = "Envio de material";
    $destinatarios = array($rowUsuario["email"]);
    $texto = "Material enviado!";
    $texto .= "\n\nNome: " . $nome;
    $texto .= "\nDescrição: " . $descricao;
    $texto .= "\nData: " . date("d-m-Y H:i:s");
    enviar_email($assunto, $destinatarios, $texto, false);

    header("Location: materiais_listar.php");
}
?>
<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Materiais - MusicSys</title>
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
                        <h1 class="page-header">Materiais - Inserir</h1>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                Materiais
                                <a href="materiais_listar.php" class="btn btn-info btn-panel-heading">Voltar</a>
                            </div>
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-lg-12">
                                        <form role="form" method="post" enctype="multipart/form-data">
                                            <div class="form-group">
                                                <label>Aluno</label>
                                                <select class="form-control" name="usuario_id" required>
                                                    <option value="">Selecione...</option>
                                                    <?php
                                                    $req = $conn->query('SELECT * FROM usuarios WHERE ativo = 1 ORDER BY nome ASC');
                                                    if ($req) {
                                                        while ($linha = $req->fetch(PDO::FETCH_ASSOC)) {
                                                            echo "<option value=" . $linha['id'] . ">" . $linha['id'] . " - " . utf8_encode($linha['nome']) . "</option>";
                                                        }
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Nome</label>
                                                <input class="form-control" type="text" name="nome" placeholder="Digite o nome" required>
                                            </div>
                                            <div class="form-group">
                                                <label>Descrição</label>
                                                <textarea class="form-control" name="descricao" placeholder="Digite uma descrição"></textarea>
                                            </div>
                                            <div class="form-group">
                                                <label>Materiais</label>
                                                <input type="file" name="materiais[]" multiple>
                                            </div>
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