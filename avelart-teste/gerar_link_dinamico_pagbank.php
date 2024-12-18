<?php

$curl = curl_init();

$data = [
    'reference_id' => 'REFERENCIA123', // Substitua por sua referência de produto
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
    'shipping' => [
        'type' => 'FREE',
        'address' => [
            'country' => 'BRA',
            'region_code' => 'SP',
            'city' => 'São Paulo',
            'postal_code' => '01452002',
            'street' => 'Faria Lima',
            'number' => '1384',
            'locality' => 'Pinheiros',
            'complement' => '5 andar'
        ]
    ],
    'payment_methods' => [
        ['type' => 'PIX'],
        ['type' => 'BOLETO']
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

if ($err) {
    echo "Erro cURL: " . $err;
} else {
    echo "Resposta da API:<br>";
    echo "<pre>" . htmlspecialchars($response) . "</pre>";
}
