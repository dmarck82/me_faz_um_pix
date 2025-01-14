<?php

//MERCADO PAGO
// define('PUBLIC_KEY', 'APP_USR-5bcb74ab-e344-491c-ae1e-cc361ef8c941');
// define('AUTH_TOKEN', 'APP_USR-1536593973055303-010313-9f21159e404283b88654c05c1c7a9fde-40592808');
define('PUBLIC_KEY', 'TEST-614ef88a-b2e8-4927-98fe-41b8e082ae5e');
define('AUTH_TOKEN', 'TEST-3188352349911828-121213-bada67594a35cb53b8034712172babe8-40592808');

define('NOTIFICATION_URL', "https://www.google.com.br");

$acess_token = AUTH_TOKEN;
$notification_url = NOTIFICATION_URL;

// Função para sanitizar e validar entrada
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

$name = strtolower(sanitize_input($_POST['name']));
$email = strtolower(sanitize_input($_POST['email']));
$value = sanitize_input($_POST['value']);
$produto = strtolower(sanitize_input($_POST['produto']));

$value = str_replace(",", ".", $value);
$value = floatval($value);

$description = $produto;

// Pagador
$pagador = [
    "first_name" => $name,
    "last_name" => "",
    "email" => $email
];

// Gera um id de compra (referencia para ser usada no seu sistema)
$externalReference = geraString(24);

// Função para gerar a string
function geraString($length) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

// Função para gerar UUID
function generateUUID() {
    return sprintf(
        '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0xffff)
    );
}

// Informações sobre o pagamento
$infos = [
    "notification_url" => $notification_url,
    "description" => $description,
    "external_reference" => $externalReference,
    "transaction_amount" => $value,
    "payment_method_id" => "pix"
];

// Encoda as informações em JSON
$payment = json_encode(array_merge(["payer" => $pagador], $infos));

// Faz o request para o Mercado Pago usando cURL
$uuid = generateUUID();
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => "https://api.mercadopago.com/v1/payments/",
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POSTFIELDS => $payment,
    CURLOPT_HTTPHEADER => [
        'Authorization: Bearer ' . $acess_token,
        'X-Idempotency-Key: ' . $uuid,
        'Content-Type: application/json'
    ]
]);

$response = curl_exec($curl);
if ($response === false) {
    die('Erro na requisição cURL: ' . curl_error($curl));
}
curl_close($curl);

// Decoda a resposta
$data = json_decode($response, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Erro ao decodificar JSON: ' . json_last_error_msg());
}

// Simplifica o caminho para a key com os dados da transação
$response = $data['point_of_interaction']['transaction_data'];

// Cria um array apenas com as informações
$arr = [
    'qr_code' => $response['qr_code'],
    'qr_code_base64' => $response['qr_code_base64'],
    'payment_url' => $response['ticket_url'],
    'id' => $data['id'],
    'ref' => $externalReference,
    'full_info_for_developer' => $data
];

// Salva informações da transação em um arquivo
$directory = "transacoes/";
if (!is_dir($directory)) {
    mkdir($directory, 0777, true);
}
$paymentId = $data['id'];
file_put_contents("$directory$externalReference", "pending;$paymentId");

// Exibe array
echo json_encode($arr);