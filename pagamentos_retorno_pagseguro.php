<?php

include './function/connection.php';
include './function/enviar_email.php';

$transacao = $_GET['transaction_id'];
$pagamento_id = $_GET['pagamento_id'];

$req = $conn->prepare('UPDATE pagamentos SET transacao = :transacao, status = 1 WHERE id = :pagamento_id');
$req->bindValue(':pagamento_id', $pagamento_id, PDO::PARAM_INT);
$req->bindValue(':transacao', $transacao, PDO::PARAM_STR);
$req->execute();

//enviar email
$assunto = "Pagamento iniciado";
$destinatarios = null;
$texto = "O pagamento foi iniciado pelo Pagseguro.";
$texto .= "\nConsulte o Pagseguro para verificar o status da transação.";
$texto .= "\n\nPagamento: " . $pagamento_id;
$texto .= "\nTransação: " . $transacao;
$texto .= "\n\nData: " . date("d-m-Y H:i:s");
enviar_email($assunto, $destinatarios, $texto, true);

header("Location: pagamentos_listar.php");
exit();
