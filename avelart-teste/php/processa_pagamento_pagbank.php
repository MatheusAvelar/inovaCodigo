<?php
header('Content-Type: application/json');

// Credenciais da API PagSeguro
$pagbank_email = "matheus_valladao@hotmail.com";
$pagbank_token = "75acd1e9-fb07-4c42-96ff-3ec516f8fe4c894e4de44aceb872f623a723dc9142b455e8-f737-427d-8970-945a76961132";
$api_url = "https://ws.pagseguro.uol.com.br/v2/checkout";

// Verificar se o valor foi enviado
if (isset($_POST['valor'])) {
    $valor = floatval($_POST['valor']);
    if ($valor <= 0) {
        echo json_encode(['success' => false, 'message' => 'Valor inválido.']);
        exit;
    }

    // Dados do pagamento
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

    // Configurar cURL para chamar a API PagBank
    $ch = curl_init($api_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        echo json_encode(['success' => false, 'message' => 'Erro ao gerar link de pagamento.']);
        curl_close($ch);
        exit;
    }

    curl_close($ch);

    // Processar resposta do PagSeguro
    $xml = simplexml_load_string($response);
    if ($xml && isset($xml->code)) {
        $paymentLink = "https://pagseguro.uol.com.br/v2/checkout/payment.html?code={$xml->code}";
        echo json_encode(['success' => true, 'payment_url' => $paymentLink]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Falha ao gerar link.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Nenhum valor foi informado.']);
}
?>
