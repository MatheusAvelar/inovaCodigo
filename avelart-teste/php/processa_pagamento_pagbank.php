<?php
$url = "https://ws.sandbox.pagseguro.uol.com.br/v2/checkout";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo "Erro cURL: " . curl_error($ch);
} else {
    echo "Conexão com PagBank estabelecida com sucesso!";
}
curl_close($ch);