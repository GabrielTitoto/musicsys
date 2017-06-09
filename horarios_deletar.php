<?php
$admin = true;
include './function/valida_login.php';
include './function/connection.php';

$id = intval($_GET['id']);
$tipo = $_GET['tipo'];

if ($tipo == "unico") {
    $req = $conn->prepare('DELETE FROM horarios WHERE id = :id');
    $req->bindValue(':id', $id, PDO::PARAM_INT);
    $req->execute();
} else {
    $req = $conn->prepare('SELECT * FROM horarios WHERE id = :id');
    $req->execute(array('id' => $id));
    if ($req) {
        while ($linha = $req->fetch(PDO::FETCH_ASSOC)) {
            $req2 = $conn->prepare('DELETE FROM horarios WHERE usuario_id = :usuario_id AND curso_id = :curso_id AND data >= :data');
            $req2->bindValue(':usuario_id', $linha['usuario_id'], PDO::PARAM_INT);
            $req2->bindValue(':curso_id', $linha['curso_id'], PDO::PARAM_STR);
            $req2->bindValue(':data', $linha['data'], PDO::PARAM_STR);
            $req2->execute();
        }
    }
}
header("Location: horarios_listar.php");