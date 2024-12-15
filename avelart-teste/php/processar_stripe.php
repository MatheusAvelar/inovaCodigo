<?php
// Habilitar exibição de erros no PHP para debug
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('vendor/autoload.php');  // Certifique-se de que o caminho está correto para o Stripe

// Definir a chave da API do Stripe
\Stripe\Stripe::setApiKey('sk_test_51QVXcjDl7Fi26zyynbuqFrvethFcM92kWyyb98XUeGW16agStI8iswpqtu9TmuxqQDXFxwgwrhCrNlIgUWPmKG1U00ZBGsCFnQ'); // Substitua com sua chave secreta

// Preparar a resposta
$response = ['status' => 'error', 'message' => ''];

try {
    // Simular recebendo o token gerado no frontend
    $token = $_POST['stripeToken']; // Esse é o token enviado pelo frontend

    if (!$token) {
        throw new Exception("Token do cartão não encontrado.");
    }

    // Criar a intenção de pagamento
    $amount = 1000; // Exemplo: valor em centavos (R$10,00)
    $paymentIntent = \Stripe\PaymentIntent::create([
        'amount' => $amount,
        'currency' => 'brl',
        'payment_method' => $token,
        'confirmation_method' => 'manual',
        'confirm' => true,
    ]);

    // Se o pagamento for bem-sucedido
    $response['status'] = 'success';
    $response['message'] = 'Pagamento realizado com sucesso!';

} catch (\Stripe\Exception\ApiErrorException $e) {
    // Erro específico do Stripe
    $response['status'] = 'error';
    $response['message'] = 'Erro no Stripe: ' . $e->getMessage();
} catch (Exception $e) {
    // Outros erros
    $response['status'] = 'error';
    $response['message'] = 'Erro geral: ' . $e->getMessage();
}

// Retornar resposta como JSON
header('Content-Type: application/json');  // Cabeçalho correto
echo json_encode($response);  // Enviar a resposta JSON
