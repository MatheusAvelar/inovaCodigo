<?php
header('Content-Type: application/json');

$url = "https://ws.pagseguro.uol.com.br/v2/checkout";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "Erro cURL: " . curl_error($ch);
} else {
    echo "Conexão com PagBank estabelecida com sucesso!";
}
curl_close($ch);

// Credenciais da API PagSeguro
$pagbank_email = "matheus_valladao@hotmail.com";
$pagbank_token = "75acd1e9-fb07-4c42-96ff-3ec516f8fe4c894e4de44aceb872f623a723dc9142b455e8-f737-427d-8970-945a76961132";
$api_url = "https://ws.sandbox.pagseguro.uol.com.br/v2/checkout";

// Verifica se o valor foi enviado corretamente
if (!isset($_POST['valor']) || empty($_POST['valor'])) {
    echo json_encode(['success' => false, 'message' => 'O valor não foi informado.']);
    exit;
}

// Formata o valor
$valor = floatval($_POST['valor']);
if ($valor <= 0) {
    echo json_encode(['success' => false, 'message' => 'Valor inválido.']);
    exit;
}

// Monta os dados da requisição
$data = http_build_query([
    'email' => $pagbank_email,
    'token' => $pagbank_token,
    'currency' => 'BRL',
    'itemId1' => '001',
    'itemDescription1' => 'Pagamento de Tatuagem',
    'itemAmount1' => number_format($valor, 2, '.', ''),
    'itemQuantity1' => 1,
    'redirectURL' => 'https://seusite.com/obrigado'
]);

// Inicializa o cURL para envio à API PagSeguro
$ch = curl_init($api_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

// Executa a requisição
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo json_encode(['success' => false, 'message' => 'Erro de conexão com o PagSeguro.']);
    curl_close($ch);
    exit;
}
curl_close($ch);

// Processa a resposta da API (em XML)
$xml = simplexml_load_string($response);

var_dump($response);

if ($xml && isset($xml->code)) {
    // Gera o link de pagamento com o código retornado
    $paymentLink = "https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code={$xml->code}";
    echo json_encode(['success' => true, 'payment_url' => $paymentLink]);
} else {
    echo json_encode(['success' => false, 'message' => 'Falha ao gerar o link de pagamento.']);
}
?>
