<?php
$accessToken = 'WPL_AP1.vC3INvUcoW1dOhlY.JXq3AA==';  // Substitua com seu Access Token

// ID da publicação do LinkedIn
$postId = 'urn:li:activity:7267883841904656384';

// Endpoint da API do LinkedIn para obter informações de uma postagem
$url = "https://api.linkedin.com/v2/ugcPosts/{$postId}";

// Inicializa a requisição cURL
$ch = curl_init($url);

// Define os headers para a requisição, incluindo o token de autenticação
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer {$accessToken}",
    "X-RestLi-Protocol-Version: 2.0.0",
]);

// Retorna a resposta como string
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Executa a requisição
$response = curl_exec($ch);

// Verifica se houve erro na requisição
if (curl_errno($ch)) {
    echo 'Erro na requisição: ' . curl_error($ch);
}

// Fecha a conexão cURL
curl_close($ch);

// Converte a resposta JSON em um array
$responseData = json_decode($response, true);

// Exibe as informações da publicação
if (isset($responseData['specificContent']['com.linkedin.ugc.ShareContent']['shareCommentary']['text'])) {
    $postContent = $responseData['specificContent']['com.linkedin.ugc.ShareContent']['shareCommentary']['text'];
} else {
    $postContent = 'Não foi possível obter o conteúdo da publicação.';
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Publicação do LinkedIn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .linkedin-post {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
        }
        .linkedin-post a {
            color: #0077b5;
            text-decoration: none;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="linkedin-post">
        <h2>Veja a Publicação no LinkedIn</h2>
        <p>Conteúdo da publicação:</p>
        <p><strong><?php echo htmlspecialchars($postContent); ?></strong></p>
        <a href="https://www.linkedin.com/feed/update/urn:li:activity:7267883841904656384/" target="_blank">
            Ver no LinkedIn
        </a>
    </div>
</body>
</html>
