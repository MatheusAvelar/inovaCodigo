<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $playlist_url = $_POST['playlist_url'];

    if (empty($playlist_url)) {
        echo "<p style='color:red;'>Por favor, forneça a URL da playlist.</p>";
    } else {
        // Chama o script Python passando a URL da playlist como argumento
        $command = escapeshellcmd("python3 baixar_playlist.py '$playlist_url'");
        $output = shell_exec($command);
        
        // Exibe a saída do script Python (resultado do download)
        echo "<pre>$output</pre>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Baixar Playlist com yt-dlp</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2rem;
        }
        .container {
            max-width: 600px;
            margin: auto;
            text-align: center;
        }
        input[type="text"], button {
            padding: 10px;
            margin: 10px 0;
            width: 100%;
        }
        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Baixar Playlist do YouTube</h1>
        <form method="POST">
            <label for="playlist_url">Cole o link da playlist:</label>
            <input type="text" id="playlist_url" name="playlist_url" placeholder="Link da Playlist">
            <button type="submit">Baixar</button>
        </form>

        <div id="result">
            <!-- A saída do PHP será exibida aqui -->
        </div>
    </div>
</body>
</html>
