<?php
function sendWhatsAppMessage($toPhoneNumber, $templateName = 'confirmar_agendamento', $languageCode = 'pt_BR', $parameters = []) {
    // URL da API do WhatsApp
    $url = 'https://graph.facebook.com/v20.0/400898223102870/messages';

    // Token de autorização (Substitua pelo seu token real)
    $token = 'EAAT8turL4I8BO4ODHhHkMZBvpZCynnHZCt57TmmCEq67qc5Lj66IbcNQX72a2s24lUtreFSuMd1b2AZBC40ZA5MxpvVPJZC0kcY1ciOh7rotKWy4f9a1ToDGfeWeZAnjneP57ZBAqeaulbYcXAeZATJU6fFEcSh27Md7vjeo0a8QirrFuxSopyZBSEfiCttuP34pKHtrjw8ZBd8LqhGNhBqmF53';

    // Dados da requisição
    $data = array(
        'messaging_product' => 'whatsapp',
        'to' => $toPhoneNumber,
        'type' => 'template',
        'template' => array(
            'name' => $templateName,
            'language' => array(
                'code' => $languageCode
            ),
            'components' => array(
                array(
                    'type' => 'body',
                    'parameters' => $parameters
                )
            )
        )
    );

    // Inicializa o cURL
    $ch = curl_init($url);

    // Configurações do cURL
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Authorization: Bearer $token",
        "Content-Type: application/json"
    ));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

    // Executa a requisição e obtém a resposta
    $response = curl_exec($ch);

    // Fecha o cURL
    curl_close($ch);

    // Retorna a resposta
    return $response;
}
