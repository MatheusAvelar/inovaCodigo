<?php
session_start();
require_once __DIR__ . '/../stripe-php/init.php';
require_once 'config.php';

\Stripe\Stripe::setApiKey(STRIPE_SECRET_KEY);

header('Content-Type: application/json');

$response = ['success' => false, 'message' => '', 'payment_url' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = $_POST['valor'] ?? '';

    try {
        // Validação básica do valor
        if (!is_numeric($valor) || $valor <= 0) {
            throw new Exception('Valor inválido. Informe um valor maior que zero.');
        }

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

        // 3. Criação da Sessão de Checkout
        $checkoutSession = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],
            'line_items' => [
                [
                    'price' => $price->id,
                    'quantity' => 1,
                ],
            ],
            'mode' => 'payment',
            'success_url' => 'http://localhost/sucesso.php',
            'cancel_url' => 'http://localhost/cancelado.php',
        ]);

        // Resposta JSON com o link de pagamento
        $_SESSION['status'] = 'success';
        $_SESSION['message'] = 'Link gerado';
        $response['success'] = true;
        $response['payment_url'] = $checkoutSession->url;

    } catch (\Exception $e) {
        $response['message'] = 'Erro ao gerar o link de pagamento: ' . $e->getMessage();
    }
}

// Retorna a resposta em formato JSON
echo json_encode($response);
