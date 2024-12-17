<?php
header('Content-Type: text/html; charset=utf-8');

// Ativa a exibição de erros para depuração
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Verifica se o formulário foi submetido via POST
$response = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Credenciais da API PagSeguro
    $pagbank_email = "matheus_valladao@hotmail.com";
    $pagbank_token = "75acd1e9-fb07-4c42-96ff-3ec516f8fe4c894e4de44aceb872f623a723dc9142b455e8-f737-427d-8970-945a76961132";
    $api_url = "https://sandbox.api.pagseguro.com/v2/checkout";

    // Valida o valor recebido
    $valor = floatval($_POST['valor'] ?? 0);
    if ($valor <= 0) {
        $response = ['success' => false, 'message' => 'Valor inválido.'];
    } else {
        // Monta os dados para envio
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

        // Inicializa o cURL
        $ch = curl_init($api_url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        $result = curl_exec($ch);

        // Imprime a resposta para depuração
        echo "<pre>" . htmlspecialchars($result) . "</pre>";

        // Trata erros de cURL
        if (curl_errno($ch)) {
            $response = ['success' => false, 'message' => 'Erro cURL: ' . curl_error($ch)];
        } else {
            // Processa a resposta da API
            $xml = simplexml_load_string($result);
            if ($xml && isset($xml->code)) {
                $paymentLink = "https://pagseguro.uol.com.br/v2/checkout/payment.html?code={$xml->code}";
                $response = ['success' => true, 'payment_url' => $paymentLink];
            } else {
                $response = ['success' => false, 'message' => 'Resposta inválida da API.', 'debug' => $result];
            }
        }
        curl_close($ch);
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
