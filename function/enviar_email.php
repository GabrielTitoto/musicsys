<?php

//Define o fuso horario como o horario de brasilia
date_default_timezone_set('America/Sao_Paulo');

function enviar_email($assunto, $destinatarios, $texto, $admin) {
    require_once('./function/PHPMailerAutoload.php');

    $mailer = new PHPMailer();
    $mailer->IsSMTP();
    $mailer->SMTPDebug = 0;
    $mailer->Port = 587; //Indica a porta de conexão para a saída de e-mails. Utilize obrigatoriamente a porta 587.
    $mailer->Host = 'smtp.gmail.com'; //Onde em 'servidor_de_saida' deve ser alterado por um dos hosts abaixo:
    $mailer->SMTPSecure = 'tls';

    $mailer->SMTPAuth = true; //Define se haverá ou não autenticação no SMTP
    $mailer->Username = 'ga_getplay@hotmail.com'; //Informe o e-mail o completo
    $mailer->Password = 'ga_0209'; //Senha da caixa postal
    $mailer->FromName = utf8_decode('MusicSys'); //Nome que será exibido para o destinatário
    $mailer->From = 'ga_getplay@hotmail.com'; //Obrigatório ser a mesma caixa postal indicada em "username"
    $mailer->Subject = utf8_decode($assunto);
    $mailer->Body = utf8_decode($texto);

    foreach ($destinatarios as $destinatario) {
        $mailer->AddAddress($destinatario); //Destinatários
    }

    //Se $admin == true, enviar uma copia para o admin
    if ($admin) {
        $mailer->AddAddress("ga_getplay@hotmail.com");
    }

    if (!$mailer->Send()) {
        echo "Erro: " . $mailer->ErrorInfo;
    } else {
        echo true;
    }
}
