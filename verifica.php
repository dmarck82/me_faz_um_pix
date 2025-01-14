<?php

//MERCADO PAGO
define('PUBLIC_KEY', 'TEST-614ef88a-b2e8-4927-98fe-41b8e082ae5e');
define('AUTH_TOKEN', 'TEST-3188352349911828-121213-bada67594a35cb53b8034712172babe8-40592808');
define('NOTIFICATION_URL', "https://www.google.com.br");

$acess_token = AUTH_TOKEN;
$notification_url = NOTIFICATION_URL;

// Função para sanitizar e validar entrada
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$reference = sanitize_input($_POST['ref']);

// Lê o ID do pagamento a partir do arquivo
$paymentData = file_get_contents("transacoes/$reference");
if ($paymentData === false) {
    die('Erro ao ler o arquivo de transações.');
}
$paymentId = explode(";", $paymentData)[1];

// Faz a requisição para a API do Mercado Pago usando cURL
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => 'https://api.mercadopago.com/v1/payments/' . $paymentId,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => 'GET',
    CURLOPT_HTTPHEADER => [
        'accept: application/json',
        'content-type: application/json',
        'Authorization: Bearer ' . $acess_token
    ]
]);

$response = curl_exec($curl);
if ($response === false) {
    die('Erro na requisição cURL: ' . curl_error($curl));
}
curl_close($curl);

$responseData = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Erro ao decodificar JSON: ' . json_last_error_msg());
}

$externalReference = $responseData['external_reference'];
$status = $responseData['status'];

// Salvar no arquivo a referência do pagamento aprovado
if ($status == "approved") {
    if (file_put_contents("transacoes/$externalReference", "approved;$paymentId") === false) {
        die('Erro ao salvar o arquivo de transações.');
    }
}
$status = "approved";
// Enviar status de volta
$arr = ['status' => $status];
echo json_encode($arr);