<?php
session_start();
// Conexão com o banco de dados
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Preparar a consulta SQL
$sql = "INSERT INTO termos_enviados (
            usuario_id, data_envio, conteudo, status, 
            nome_responsavel, rg_responsavel, cpf_responsavel, nascimento_responsavel, 
            nome_cliente, email_cliente, rg_cliente, cpf_cliente, nascimento_cliente, 
            local_tatuagem, data_tatuagem, nome_tatuador, cicatrizacao, desmaio, 
            hemofilico, hepatite, hiv, autoimune, epileptico, medicamento, alergia, 
            assinatura_responsavel
        ) VALUES (
            ?, NOW(), ?, 'ativo', 
            ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, 
            ?, ?, ?, ?, ?, ?, ?
        )";

// Preparar a declaração
$stmt = $conn->prepare($sql);

// Verifique se a preparação da declaração foi bem-sucedida
if ($stmt === false) {
    die("Erro na preparação da consulta: " . $conn->error);
}

// Obter o conteúdo do PDF como string
ob_start();
$pdf->Output('S');
$pdfContent = ob_get_clean();

// Vincular parâmetros
$stmt->bind_param(
    "sssssssssssssssssssssssss", 
    $usuario_id, $pdfContent, 
    $nome_responsavel, $rg_responsavel, $cpf_responsavel, $nascimento_responsavel, 
    $nome_cliente, $email_cliente, $rg_cliente, $cpf_cliente, $nascimento_cliente, 
    $local_tatuagem, $data_tatuagem, $nome_tatuador, $cicatrizacao, $desmaio, 
    $hemofilico, $hepatite, $hiv, $autoimune, $epileptico, $medicamento, $alergia, 
    $assinatura_responsavel
);

// Executar a declaração
if ($stmt->execute()) {
    $_SESSION['message'] .= "Termo salvo com sucesso.";
} else {
    $_SESSION['message'] .= "Erro ao salvar o termo: " . $stmt->error;
}

// Fechar a declaração e a conexão
$stmt->close();
$conn->close();