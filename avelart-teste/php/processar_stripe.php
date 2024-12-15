<?php

// Carregar a biblioteca do Stripe manualmente
require_once('../stripe-php/init.php');  // Caminho para a pasta stripe-php extraída

// Configurações do Stripe
\Stripe\Stripe::setApiKey('sk_test_51QVXcjDl7Fi26zyynbuqFrvethFcM92kWyyb98XUeGW16agStI8iswpqtu9TmuxqQDXFxwgwrhCrNlIgUWPmKG1U00ZBGsCFnQ'); // Substitua com sua chave secreta

// Recebe os dados enviados via POST
$input = json_decode(file_get_contents('php://input'), true);

$name = $input['name'];
$email = $input['email'];
$amount = $input['amount'] * 100; // Valor em centavos (ex: 100.00 se R$100,00)
$description = $input['description'];
$token = $input['token']; // Token gerado pelo Stripe

try {
    // Criação do PaymentIntent
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'brl',
        'payment_method' => $token,
        'confirmation_method' => 'manual',
        'confirm' => true,
        'description' => $description,
        'receipt_email' => $email,
    ]);

    // Verifica se o pagamento foi bem-sucedido
    if ($paymentIntent->status === 'succeeded') {
        echo json_encode(['status' => 'success', 'message' => 'Pagamento realizado com sucesso!']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Pagamento não concluído']);
    }
} catch (\Stripe\Exception\ApiErrorException $e) {
    // Se ocorrer um erro no Stripe
    echo json_encode(['status' => 'error', 'message' => 'Erro ao processar o pagamento: ' . $e->getMessage()]);
}

?>
