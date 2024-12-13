<?php

// Configurações iniciais do PagSeguro
define('PAGSEGURO_EMAIL', 'matheus_valladao@hotmail.com');
define('PAGSEGURO_TOKEN', '88E1B6800CFC49978ECE7C0B994C7EB0');
define('PAGSEGURO_URL', 'https://ws.sandbox.pagbank.com.br/v2/checkout'); // Sandbox. Use https://ws.pagseguro.uol.com.br/v2/checkout para produção.

// Verifique se os dados foram enviados
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $amount = filter_input(INPUT_POST, 'amount', FILTER_SANITIZE_STRING);
    $description = filter_input(INPUT_POST, 'description', FILTER_SANITIZE_STRING);

    if (!$name || !$email || !$amount || !$description) {
        echo "Por favor, preencha todos os campos.";
        exit;
    }

    // Dados da requisição para o PagSeguro
    $data = [
        'email' => PAGSEGURO_EMAIL,
        'token' => PAGSEGURO_TOKEN,
        'currency' => 'BRL',
        'itemId1' => '0001',
        'itemDescription1' => $description,
        'itemAmount1' => number_format($amount, 2, '.', ''),
        'itemQuantity1' => 1,
        'reference' => 'PEDIDO12345',
        'senderName' => $name,
        'senderEmail' => $email,
    ];

    // Iniciar a conexão com o PagSeguro
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, PAGSEGURO_URL);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/x-www-form-urlencoded; charset=ISO-8859-1'
    ]);

    // Executar a requisição
    $response = curl_exec($ch);
    curl_close($ch);

    // Verificar a resposta
    if ($response === false) {
        echo "Erro ao conectar ao PagSeguro.";
        exit;
    }

    // Interpretar a resposta XML
    $xml = simplexml_load_string($response);

    if (isset($xml->code)) {
        // Redirecionar o cliente para a página de pagamento
        $checkoutUrl = "https://sandbox.pagseguro.uol.com.br/v2/checkout/payment.html?code=" . $xml->code;
        header("Location: $checkoutUrl");
        exit;
    } else {
        echo "Erro ao processar o pagamento: " . $response;
    }
} else {
    echo "Requisição inválida.";
}

?>
