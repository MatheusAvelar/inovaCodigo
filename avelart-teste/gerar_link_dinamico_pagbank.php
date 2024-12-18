<?php
$response = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = filter_input(INPUT_POST, 'valor', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

    if ($valor) {
        $email = "matheus_valladao@hotmail.com";
        $token = "88E1B6800CFC49978ECE7C0B994C7EB0";

        $payload = [
            'reference_id' => 'TATUAGEM_' . time(),
            'customer' => [
                'name' => 'Cliente Exemplo',
                'email' => 'cliente@exemplo.com',
                'tax_id' => '12345678909',
                'phone' => [
                    'country' => '+55',
                    'area' => '27',
                    'number' => '999999999'
                ]
            ],
            'items' => [
                [
                    'reference_id' => 'ITEM01',
                    'name' => 'Serviço de Tatuagem',
                    'quantity' => 1,
                    'unit_amount' => (int)($valor * 100), // Convertendo para centavos
                ]
            ],
            'redirect_url' => 'https://seusite.com.br/sucesso',
            'notification_urls' => [
                'https://seusite.com.br/notificacao'
            ]
        ];

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => "https://sandbox.api.pagseguro.com/checkout",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => json_encode($payload),
            CURLOPT_HTTPHEADER => [
                "Content-Type: application/json",
                "Authorization: Bearer $token"
            ]
        ]);

        $result = curl_exec($ch);
        $error = curl_error($ch);
        curl_close($ch);

        if ($error) {
            $response = [
                'success' => false,
                'message' => "Erro na solicitação: $error",
                'debug' => $result
            ];
        } else {
            $result = json_decode($result, true);

            if (isset($result['id'])) {
                $response = [
                    'success' => true,
                    'payment_url' => $result['links'][0]['href'] ?? null
                ];
            } else {
                $response = [
                    'success' => false,
                    'message' => "Erro ao criar o pagamento: " . ($result['message'] ?? 'Erro desconhecido'),
                    'debug' => json_encode($result, JSON_PRETTY_PRINT)
                ];
            }
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'Valor inválido informado!'
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Link de Pagamento</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        input, button { padding: 10px; margin-top: 10px; border: 1px solid #ccc; border-radius: 4px; width: 100%; }
        button { background-color: #007bff; color: white; cursor: pointer; }
        .success, .error { margin-top: 10px; padding: 10px; border-radius: 4px; }
        .success { background-color: #28a745; color: white; }
        .error { background-color: #dc3545; color: white; }
        a { display: inline-block; margin-top: 10px; background-color: #28a745; color: white; padding: 8px 12px; text-decoration: none; border-radius: 5px; font-weight: bold; }
    </style>
</head>
<body>
    <h2>Gerar Link de Pagamento - Valor Dinâmico</h2>
    <form method="POST" action="">
        <label for="valor">Informe o valor da tatuagem (R$):</label>
        <input type="text" id="valor" name="valor" placeholder="Ex: 150.00" required>
        <button type="submit">Gerar Link</button>
    </form>

    <?php if ($response): ?>
        <div class="<?= $response['success'] ? 'success' : 'error' ?>">
            <?= $response['success'] 
                ? "Link gerado com sucesso: <a href='{$response['payment_url']}' target='_blank'>Pagar no PagSeguro</a>" 
                : "Erro: {$response['message']}" ?>
        </div>

        <?php if (isset($response['payment_url'])): ?>
            <a href="https://wa.me/?text=Aqui está o link de pagamento: <?= urlencode($response['payment_url']) ?>" target="_blank">
                Compartilhar no WhatsApp
            </a>
        <?php endif; ?>

        <?php if (isset($response['debug'])): ?>
            <pre><?= htmlspecialchars($response['debug']) ?></pre>
        <?php endif; ?>
    <?php endif; ?>
</body>
</html>
