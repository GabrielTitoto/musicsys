<?php

include './function/connection.php';
include './function/enviar_email.php';

$req = $conn->query("SELECT P.id, U.nome as usuario, U.email, I.nome as curso, I.valor, P.status, P.vencimento FROM pagamentos P, usuarios U, status_pagamento S, cursos I WHERE P.usuario_id = U.id AND P.curso_id = I.id AND P.status = S.id AND P.status <> 3 AND P.status <> 4 ORDER BY usuario ASC, vencimento ASC");

if ($req) {
    while ($linha = $req->fetch(PDO::FETCH_ASSOC)) {
        //Calcular vencimento
        $vencimento = strtotime($linha['vencimento']) - strtotime(date('Y-m-d'));
        $vencimento = floor($vencimento / (60 * 60 * 24));


        //enviar email
        $assunto = "Lembrete de pagamento";
        $destinatarios = array($linha['email']);
        $texto = null;
        
        if ($vencimento == 5) {
            $texto = "Olá " . utf8_decode($linha['usuario']) . "!";
            $texto .= "\nSua fatura vence em 5 dias.";
            $texto .= "\n\nCurso: " . utf8_decode($linha['curso']);
            $texto .= "\n\nSe você já efetuou o pagamento, por favor, desconsidere essa mensagem.";
            enviar_email($assunto, $destinatarios, $texto, false);
        } elseif ($vencimento == 4) {
            $texto = "Olá " . utf8_decode($linha['usuario']) . "!";
            $texto .= "\nSua fatura vence em 4 dias.";
            $texto .= "\n\nCurso: " . utf8_decode($linha['curso']);
            $texto .= "\n\nSe você já efetuou o pagamento, por favor, desconsidere essa mensagem.";
            enviar_email($assunto, $destinatarios, $texto, false);
        } elseif ($vencimento == 3) {
            $admin = true;
            $texto = "Olá " . utf8_decode($linha['usuario']) . "!";
            $texto .= "\nSua fatura vence em 3 dias.";
            $texto .= "\n\nCurso: " . utf8_decode($linha['curso']);
            $texto .= "\n\nSe você já efetuou o pagamento, por favor, desconsidere essa mensagem.";
            enviar_email($assunto, $destinatarios, $texto, true);
        } elseif ($vencimento == 2) {
            $texto = "Olá " . utf8_decode($linha['usuario']) . "!";
            $texto .= "\nSua fatura vence em 2 dias.";
            $texto .= "\n\nCurso: " . utf8_decode($linha['curso']);
            $texto .= "\n\nSe você já efetuou o pagamento, por favor, desconsidere essa mensagem.";
            enviar_email($assunto, $destinatarios, $texto, false);
        } elseif ($vencimento == 1) {
            $texto = "Olá " . utf8_decode($linha['usuario']) . "!";
            $texto .= "\nSua fatura vence amanhã.";
            $texto .= "\n\nCurso: " . utf8_decode($linha['curso']);
            $texto .= "\n\nSe você já efetuou o pagamento, por favor, desconsidere essa mensagem.";
            enviar_email($assunto, $destinatarios, $texto, false);
        } elseif ($vencimento == 0) {
            $texto = "Olá " . utf8_decode($linha['usuario']) . "!";
            $texto .= "\nSua fatura vence hoje.";
            $texto .= "\n\nCurso: " . utf8_decode($linha['curso']);
            $texto .= "\n\nSe você já efetuou o pagamento, por favor, desconsidere essa mensagem.";
            enviar_email($assunto, $destinatarios, $texto, false);
        }

        if (isset($texto)) {
            echo $texto . "<br><br>";
        }
    }
}