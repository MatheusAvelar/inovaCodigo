<?php
header('Content-Type: application/json');

// Credenciais da API PagBank
$pagbank_email = "matheus_valladao@hotmail.com";
$pagbank_token = "75acd1e9-fb07-4c42-96ff-3ec516f8fe4c894e4de44aceb872f623a723dc9142b455e8-f737-427d-8970-945a76961132";
$api_url = "https://ws.pagseguro.uol.com.br/v2/checkout";

// Validação do valor informado
if (!isset($_POST['valor']) || empty($_POST['valor'])) {
    echo json_encode(['success' => false, 'message' => 'O valor não foi informado.']);
    exit;
}

$valor = floatval($_POST['valor']);
if ($valor <= 0) {
    echo json_encode(['success' => false, 'message' => 'Valor inválido.']);
    exit;
}

// Monta os dados para envio
$data = http_build_query([
    'email' => $pagbank_email,
    'token' => $pagbank_token,
    'currency' => 'BRL',
    'itemId1' => '001',
    'itemDescription1' => 'Pagamento de Tatuagem',
    'itemAmount1' => number_format($valor, 2, '.', ''),
    'itemQuantity1' => 1,
    'redirectURL' => 'https://seudominio.com/obrigado'
]);

// Inicializa o cURL
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Certificado SSL

// Executa a requisição
$response = curl_exec($ch);

// Debug: Verificar erros no cURL
if (curl_errno($ch)) {
    echo json_encode(['success' => false, 'message' => 'Erro cURL: ' . curl_error($ch)]);
    curl_close($ch);
    exit;
}

curl_close($ch);

// Processa a resposta da API
$xml = simplexml_load_string($response);

if ($xml && isset($xml->code)) {
    $paymentLink = "https://pagseguro.uol.com.br/v2/checkout/payment.html?code={$xml->code}";
    echo json_encode(['success' => true, 'payment_url' => $paymentLink]);
} else {
    echo json_encode(['success' => false, 'message' => 'Falha ao gerar o link de pagamento.', 'response' => $response]);
}
?>
