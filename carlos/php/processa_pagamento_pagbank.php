<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = $_POST['valor'];
    $valorEmCentavos = (int) (floatval($valor) * 100); // Converte para centavos

    echo "Valor recebido: " . $valor . "<br>";
    echo "Valor em centavos: " . $valorEmCentavos . "<br>";
    
    $curl = curl_init();

    $nomeCliente = $_POST['nome'];
    $emailCliente = $_POST['email'];
    $cpfCliente = preg_replace('/\D/', '', $_POST['cpf']);
    $telefoneCliente = preg_replace('/\D/', '', $_POST['telefone']); 
    $ddd = substr($telefoneCliente, 0, 2);
    $numero = substr($telefoneCliente, 2);
    $imageUrl = 'https://phleboexperience.com.br/images-event/logo_menu.png';
    $expirationDate = date('Y-m-d\TH:i:sP', strtotime('+2 days'));

    $data = [
        'reference_id' => 'REFERENCIA123',
        'expiration_date' => $expirationDate,
        'customer' => [
            'name' => '',
            'email' => '',
            'tax_id' => '',
            'phone' => [
                'country' => '+55',
                'area' => '',
                'number' => '',
            ],
        ],
        'customer_modifiable' => true,
        'items' => [
            [
                'reference_id' => 'ITEM01',
                'name' => 'Teste',
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
            "Authorization: Bearer 88E1B6800CFC49978ECE7C0B994C7EB0",
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

        // Exibe a resposta da API para depuração
        echo json_encode(['response' => $data]);
        
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
