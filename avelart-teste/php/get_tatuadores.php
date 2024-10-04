<?php
include 'utils.php';

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

$query = "SELECT id, UPPER(nome) AS nome FROM usuarioEstudio WHERE ativo = '1'";
$result = $conn->query($query);

if ($result === false) {
    die('Erro na consulta SQL: ' . $conn->error);
}

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $id = htmlspecialchars($row['id']);
        $nome = htmlspecialchars($row['nome']);
        $selected = (isset($_GET['filter_tatuador']) && $_GET['filter_tatuador'] == $id) ? 'selected' : '';
        echo "<option value=\"$id\" $selected>$nome</option>";
    }
} else {
    echo '<option value="">Nenhum tatuador encontrado</option>';
}

$conn->close();
?>
