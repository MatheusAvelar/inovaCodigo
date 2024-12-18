<?php

$curl = curl_init();

$data = [
    'reference_id' => 'REFERENCIA123',
    'expiration_date' => '2024-12-31T19:09:10-03:00',
    'customer' => [
        'name' => 'João Teste',
        'email' => 'joao@teste.com',
        'tax_id' => '12345678909',
        'phone' => [
            'country' => '+55',
            'area' => '27',
            'number' => '999999999'
        ]
    ],
    'customer_modifiable' => true,
    'items' => [
        [
            'reference_id' => 'ITEM01',
            'name' => 'Nome do Produto',
            'quantity' => 1,
            'unit_amount' => 500,
            'image_url' => 'https://www.petz.com.br/blog//wp-content/upload/2018/09/tamanho-de-cachorro-pet-1.jpg'
        ]
    ],
    'payment_methods' => [
        ['type' => 'PIX'],
        ['type' => 'BOLETO'],
        ['type' => 'DEBIT_CARD'],
        ['type' => 'CREDIT_CARD']
    ],
    'redirect_url' => 'https://pagseguro.uol.com.br',
    'notification_urls' => [
        'https://meusite.com.br/notificacao'
    ]
];

curl_setopt_array($curl, [
    CURLOPT_URL => "https://sandbox.api.pagseguro.com/checkouts",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer 88E1B6800CFC49978ECE7C0B994C7EB0",
        "Content-Type: application/json",
    ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

$data = json_decode($response, true);

// Inicializa a variável para o link de pagamento
$linkPagamento = null;

// Percorre o array de links para encontrar o que possui "rel" igual a "PAY"
foreach ($data['links'] as $link) {
    if ($link['rel'] === 'PAY') {
        $linkPagamento = $link['href'];
        break;
    }
}

// Exibe o link de pagamento
if ($linkPagamento) {
    echo "Link de Pagamento: " . $linkPagamento;
}