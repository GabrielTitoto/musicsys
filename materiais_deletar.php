<?php
$admin = true;
include './function/valida_login.php';
include './function/connection.php';

$id = intval($_GET['id']);

$req = $conn->prepare('DELETE FROM materiais WHERE id = :id');
$req->bindValue(':id', $id, PDO::PARAM_STR);
$req->execute();

header("Location: materiais_listar.php");
