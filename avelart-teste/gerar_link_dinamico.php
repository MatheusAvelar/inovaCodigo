<?php
// Inclua a biblioteca do Stripe manualmente
require_once __DIR__ . '/stripe-php/init.php';

// Defina a chave secreta do Stripe
\Stripe\Stripe::setApiKey('sk_test_51QVXcjDl7Fi26zyynbuqFrvethFcM92kWyyb98XUeGW16agStI8iswpqtu9TmuxqQDXFxwgwrhCrNlIgUWPmKG1U00ZBGsCFnQ'); // Substitua pela sua chave secreta

// Inicialização de variáveis
$paymentLinkUrl = '';
$error = '';

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = $_POST['valor'] ?? '';

    try {
        // 1. Criação do Produto
        $product = \Stripe\Product::create([
            'name' => 'Tatuagem Personalizada',
            'description' => 'Tatuagem feita sob medida',
        ]);

        // 2. Criação do Preço
        $price = \Stripe\Price::create([
            'unit_amount' => $valor * 100, // Valor em centavos
            'currency' => 'brl',
            'product' => $product->id,
        ]);

        // 3. Criar Link de Pagamento
        $paymentLink = \Stripe\PaymentLink::create([
            'line_items' => [
                [
                    'price' => $price->id,
                    'quantity' => 1,
                ],
            ],
        ]);

        // Exibir o link gerado
        echo 'Link de pagamento: <a href="' . $paymentLink->url . '" target="_blank">Clique aqui para pagar</a>';

    } catch (\Stripe\Exception\ApiErrorException $e) {
        echo 'Erro ao criar o link de pagamento: ' . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerar Link de Pagamento Dinâmico</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        input { padding: 8px; margin-top: 10px; border: 1px solid #ccc; border-radius: 4px; width: 100%; }
        button { margin-top: 10px; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        a { display: inline-block; margin-top: 10px; text-decoration: none; background-color: #28a745; color: white; padding: 10px 15px; border-radius: 4px; }
        .error { color: red; margin-top: 10px; }
    </style>
</head>
<body>
    <h2>Gerar Link de Pagamento - Valor Dinâmico</h2>
    <form method="POST" action="">
        <label for="valor">Informe o valor da tatuagem (R$):</label>
        <input type="number" step="0.01" min="0.01" name="valor" id="valor" placeholder="Ex: 150.00" required>
        <button type="submit">Gerar Link de Pagamento</button>
    </form>

    <?php if ($paymentLinkUrl): ?>
        <p>Link de Pagamento Criado com Sucesso:</p>
        <a href="<?php echo htmlspecialchars($paymentLinkUrl); ?>" target="_blank">Ir para o Pagamento</a>

        <p>Compartilhe este link com o cliente para que ele possa realizar o pagamento.</p>
        <textarea rows="3" cols="50" readonly><?php echo htmlspecialchars($paymentLinkUrl); ?></textarea>
    <?php elseif ($error): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
</body>
</html>