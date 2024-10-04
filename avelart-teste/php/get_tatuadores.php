<?php
include 'utils.php';

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

// Obtendo a lista de tatuadores
$query = "SELECT id, nome FROM usuarioEstudio";
$result = $conn->query($query);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = htmlspecialchars($row['id']);
        $nome = htmlspecialchars($row['nome']);
        $selected = (isset($_GET['filter_tatuador']) && $_GET['filter_tatuador'] == $id) ? 'selected' : '';
        echo "<option value=\"$id\" $selected>$nome</option>";
    }
}

// Fechando a conexão
//$conn->close();
?>
