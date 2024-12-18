<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = $_POST['valor'];
    $valorEmCentavos = (int) (floatval($valor) * 100); // Converte para centavos

    $curl = curl_init();

    // Dados do cliente (valores fictícios)
    $nomeCliente = "João Teste";
    $emailCliente = "joao@teste.com";
    $cpfCliente = "12345678909";
    $telefoneCliente = "999999999";
    $imageUrl = 'https://' . $_SERVER['HTTP_HOST'] . '/img/tatto.jpg';

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
                'image_url' => $imageUrl,
            ],
        ],
        'payment_methods' => [
            ['type' => 'PIX'],
            ['type' => 'BOLETO'],
            ['type' => 'DEBIT_CARD'],
            ['type' => 'CREDIT_CARD'],
        ],
        'redirect_url' => 'https://avelart-teste.inovacodigo.com.br',
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

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo json_encode(['success' => false, 'message' => "Erro cURL: $err"]);
    } else {
        $data = json_decode($response, true);

        if (isset($data['links'])) {
            foreach ($data['links'] as $link) {
                if ($link['rel'] === 'PAY') {
                    echo json_encode(['success' => true, 'payment_url' => $link['href']]);
                    exit;
                }
            }
        }

        echo json_encode(['success' => false, 'message' => 'Não foi possível gerar o link de pagamento.']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Acesso inválido.']);
}
