<?php

$admin = false;
include './function/valida_login.php';
include './function/connection.php';

$id = intval($_GET['id']);

$req = $conn->prepare("SELECT P.id, P.usuario_id, P.curso_id, U.nome as usuario, U.email, T.numero, I.nome as curso, I.valor, P.vencimento FROM pagamentos P, usuarios U, telefones T, cursos I WHERE P.id = :id AND P.usuario_id = U.id AND P.curso_id = I.id AND P.usuario_id = T.usuario_id AND T.tipo = 'celular'");
$req->execute(array('id' => $id));
$linha = $req->fetch(PDO::FETCH_ASSOC);

//$url = 'https://ws.pagseguro.uol.com.br/v2/checkout';
$url = 'https://ws.sandbox.pagseguro.uol.com.br/v2/checkout';

//Calcular vencimento
$vencimento = strtotime($linha['vencimento']) - strtotime(date('Y-m-d'));
$vencimento = floor($vencimento / (60 * 60 * 24));

//Adicionar 10% de juros se vencido
$msgVencimento = "";
$juros = 0;
if ($vencimento < 0) {
    $msgVencimento = " + juros por atraso";
    $juros = $linha['valor'] * 0.1;
}

$pagamento['itemId1'] = '0001';
$pagamento['itemDescription1'] = 'Aula de ' . $linha['curso'] . $msgVencimento;
$pagamento['itemAmount1'] = number_format($linha['valor'] + $juros, 2);
$pagamento['itemQuantity1'] = '1';
$pagamento['itemWeight1'] = '1';
$pagamento['reference'] = 'pagamento_' . $linha['id'];
$pagamento['senderName'] = $linha['usuario'];
$pagamento['senderAreaCode'] = substr($linha['numero'], 0, 2);
$pagamento['senderPhone'] = substr($linha['numero'], 2);
$pagamento['senderEmail'] = $linha['email'];

$pagamento['email'] = 'gabrieltitoto@gmail.com';
$pagamento['token'] = '414FBE68DD9142E3A08DE66840822EE1';
$pagamento['currency'] = 'BRL';
$pagamento['shippingType'] = '1';
$pagamento['shippingAddressStreet'] = 'Av. Brig. Faria Lima';
$pagamento['shippingAddressNumber'] = '1384';
$pagamento['shippingAddressComplement'] = '5o andar';
$pagamento['shippingAddressDistrict'] = 'Jardim Paulistano';
$pagamento['shippingAddressPostalCode'] = '01452002';
$pagamento['shippingAddressCity'] = 'Sao Paulo';
$pagamento['shippingAddressState'] = 'SP';
$pagamento['shippingAddressCountry'] = 'BRA';
$pagamento['redirectURL'] = 'http://localhost/musicsys/pagamentos_retorno_pagseguro.php?pagamento_id=' . $linha['id'];
$pagamento['notificationURL'] = 'http://localhost/musicsys/pagamentos_notificacao_pagseguro.php?pagamento_id=' . $linha['id'];

$pagamento = http_build_query($pagamento);

$curl = curl_init($url);

curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
curl_setopt($curl, CURLOPT_POSTFIELDS, $pagamento);
$xml = curl_exec($curl);
curl_close($curl);

$erro = "";
if ($xml == 'Unauthorized') {
    $erro = "autenticacao";
}

$simplexml = simplexml_load_string($xml);
if (count($simplexml->error) > 0) {
    $erro = $simplexml->error->code . ' - ' . $simplexml->error->message;
}

$req2 = $conn->prepare('UPDATE pagamentos SET xml = :xml, pagamento = :pagamento WHERE id = :id');
$req2->bindValue(':id', $id, PDO::PARAM_INT);
$req2->bindValue(':pagamento', $pagamento, PDO::PARAM_STR);
$req2->bindValue(':xml', $xml, PDO::PARAM_STR);
$req2->execute();

if ($erro != "") {
    header('Location: http://localhost/musicsys/erro.php?erro=' . $erro);
} else {
    //header('Location: https://pagseguro.uol.com.br/v2/checkout/payment.html?code=' . $simplexml->code);
    header('Location: https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=' . $simplexml->code);
}
