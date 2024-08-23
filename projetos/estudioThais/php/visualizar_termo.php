<?php
require('../fpdf/fpdf.php');

// Conectar ao banco de dados
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

$conn = new mysqli($servername, $username, $password, $dbname);

// Verificar conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obter o ID passado pela URL
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id === 0) {
    die("ID inválido.");
}

// Recuperar os dados da tabela 'termos_enviados' para o ID especificado
$sql = "SELECT * FROM termos_enviados WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

if (!$data) {
    die("Nenhum dado encontrado para o ID: " . $id);
}

// Criar o PDF usando FPDF
$pdf = new FPDF();
$pdf->AddPage();

// Cabeçalho
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Termo de Responsabilidade', 0, 1, 'C');

// Espaçamento
$pdf->Ln(10);

// Nome do Responsável
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Nome do Responsavel:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['nome_responsavel'], 0, 1);

// RG do Responsável
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'RG do Responsavel:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['rg_responsavel'], 0, 1);

// CPF do Responsável
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'CPF do Responsavel:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['cpf_responsavel'], 0, 1);

// Data de Nascimento do Responsável
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Nascimento do Responsavel:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, date('d/m/Y', strtotime($data['nascimento_responsavel'])), 0, 1);

// Nome do Cliente
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Nome do Cliente:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['nome_cliente'], 0, 1);

// Email do Cliente
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Email do Cliente:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['email_cliente'], 0, 1);

// RG do Cliente
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'RG do Cliente:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['rg_cliente'], 0, 1);

// CPF do Cliente
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'CPF do Cliente:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['cpf_cliente'], 0, 1);

// Data de Nascimento do Cliente
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Nascimento do Cliente:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, date('d/m/Y', strtotime($data['nascimento_cliente'])), 0, 1);

// Local da Tatuagem
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Local da Tatuagem:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['local_tatuagem'], 0, 1);

// Data da Tatuagem
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Data da Tatuagem:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, date('d/m/Y', strtotime($data['data_tatuagem'])), 0, 1);

// Nome do Tatuador
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Nome do Tatuador:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['nome_tatuador'], 0, 1);

// Cicatrização
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(50, 10, 'Cicatrizacao:');
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, ucfirst($data['cicatrizacao']), 0, 1);

// Assinatura do Responsável
$pdf->Ln(20);
$pdf->SetFont('Arial', 'B', 12);
$pdf->Cell(0, 10, 'Assinatura do Responsavel:', 0, 1, 'L');
$pdf->Ln(10);
$pdf->SetFont('Arial', '', 12);
$pdf->Cell(0, 10, $data['assinatura_responsavel'], 0, 1, 'L');

// Fechar a conexão
$stmt->close();
$conn->close();

// Exibir o PDF no navegador
$pdf->Output('I', 'termo_autorizacao.pdf');
?>
