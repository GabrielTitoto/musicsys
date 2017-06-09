<?php

$admin = false;
include './valida_login.php';
include './connection.php';

$horarios = array();

if ($_SESSION["usuario"]["admin"]) {
    $req = $conn->query('SELECT H.id as id, U.nome as usuario, I.nome as curso, H.data, H.hora, H.local FROM horarios H, usuarios U, cursos I WHERE H.usuario_id = U.id AND H.curso_id = I.id');
} else {
    $req = $conn->prepare('SELECT H.id as id, U.nome as usuario, I.nome as curso, H.data, H.hora, H.local FROM horarios H, usuarios U, cursos I WHERE H.usuario_id = U.id AND H.curso_id = I.id AND U.id = :usuario_id');
    $req->execute(array('usuario_id' => $_SESSION["usuario"]["id"]));
}

if ($req) {
    while ($linha = $req->fetch(PDO::FETCH_ASSOC)) {
        $start = date(DATE_ISO8601, strtotime($linha['data'] . ' ' . $linha['hora']));
        $end = date(DATE_ISO8601, strtotime($linha['data'] . ' ' . $linha['hora']) + 60 * 60);

        $e = array();
        $e['id'] = $linha['id'];
        $e['title'] = utf8_encode($linha['usuario']) . " - " . utf8_encode($linha['curso'] . " (" . $linha['local'] . ")");
        $e['start'] = $start;
        $e['end'] = $end;
        $e['allDay'] = false;

        array_push($horarios, $e);
    }
}
echo json_encode($horarios);

