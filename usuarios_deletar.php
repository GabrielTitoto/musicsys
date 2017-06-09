<?php
$admin = true;
include './function/valida_login.php';
include './function/connection.php';

$id = $_GET['id'];
$id = intval($id);

$req = $conn->prepare('DELETE FROM usuarios WHERE id = :id');
$req->bindValue(':id', $id, PDO::PARAM_STR);
$req->execute();

$req2 = $conn->prepare('DELETE FROM telefones WHERE usuario_id = :id');
$req2->bindValue(':id', $id, PDO::PARAM_STR);
$req2->execute();

header("Location: usuarios_listar.php");
