<?php
function sendWhatsAppMessage($toPhoneNumber, $templateName = 'bem_vindo', $languageCode = 'pt_BR') {
    // URL da API do WhatsApp
    $url = 'https://graph.facebook.com/v20.0/400898223102870/messages';

    // Token de autorização (Substitua pelo seu token real)
    $token = 'EAAT8turL4I8BOz0ZANVjZB0kG5M6WAqcgHDB6zWzVtig9Hp69jsqhGzCj2lpxeZBeWYRM7by7iIiytFVXjZASwgDGHxg096IalDwMHTgKfCrjZAXTRQ3Mpwfl9W4ZCwHR5Sf84nSxqW1nV5E8Rw9aglrHOXpi4j7b682BW6l26fDHnlGbC2sC9Kv4CZBrtMZBdE9CDR57Y0bEmKLrqD6GrcZD';

    // Dados da requisição
    $data = array(
        'messaging_product' => 'whatsapp',
        'to' => $toPhoneNumber,
        'type' => 'template',
        'template' => array(
            'name' => $templateName,
            'language' => array(
                'code' => $languageCode
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
?>
