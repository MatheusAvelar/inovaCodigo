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

    <?php
    $response = null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $valor = $_POST['valor'];
        $valorEmCentavos = (int) (floatval($valor) * 100); // Converte para centavos

        $curl = curl_init();

        // Dados do cliente (valores fictícios)
        $nomeCliente = "João Teste";
        $emailCliente = "joao@teste.com";
        $cpfCliente = "12345678909";
        $telefoneCliente = "999999999";
        $imagem = "https://www.exemplo.com/imagem.jpg";

        $data = [
            'reference_id' => 'REFERENCIA123',
            'expiration_date' => '2024-12-31T19:09:10-03:00',
            'customer' => [
                'name' => $nomeCliente,
                'email' => $emailCliente,
                'tax_id' => $cpfCliente,
                'phone' => [
                    'country' => '+55',
                    'area' => '27',
                    'number' => $telefoneCliente,
                ],
            ],
            'customer_modifiable' => true,
            'items' => [
                [
                    'reference_id' => 'ITEM01',
                    'name' => 'Tatuagem',
                    'quantity' => 1,
                    'unit_amount' => $valorEmCentavos,
                    'image_url' => $imagem,
                ],
            ],
            'payment_methods' => [
                ['type' => 'PIX'],
                ['type' => 'BOLETO'],
                ['type' => 'DEBIT_CARD'],
                ['type' => 'CREDIT_CARD'],
            ],
            'redirect_url' => 'https://avelart-teste.inovacodigo.com.br',
            'notification_urls' => [
                'https://meusite.com.br/notificacao',
            ],
        ];

        curl_setopt_array($curl, [
            CURLOPT_URL => "https://sandbox.api.pagseguro.com/checkouts",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => json_encode($data),
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer 88E1B6800CFC49978ECE7C0B994C7EB0", // Substitua pelo seu token real
                "Content-Type: application/json",
            ],
        ]);

        $apiResponse = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
            $response = [
                'success' => false,
                'message' => "Erro cURL: " . $err,
            ];
        } else {
            $data = json_decode($apiResponse, true);

            if (isset($data['links'])) {
                foreach ($data['links'] as $link) {
                    if ($link['rel'] === 'PAY') {
                        $response = [
                            'success' => true,
                            'payment_url' => $link['href'],
                        ];
                        break;
                    }
                }
            }

            if (!$response) {
                $response = [
                    'success' => false,
                    'message' => "Não foi possível gerar o link de pagamento.",
                    'debug' => $apiResponse,
                ];
            }
        }
    }
    ?>

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
