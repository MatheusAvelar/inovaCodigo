<?php
/*include 'utils.php';

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

$query = "SELECT id, 
                CONCAT(UPPER(LEFT(nome, 1)), LOWER(SUBSTRING(nome, 2))) AS nome
            FROM usuarioEstudio 
            WHERE ativo = 1";

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

$conn->close();*/
// Inclua a função de conexão com o banco de dados
include 'utils.php';

try {
    // Conecte-se ao banco de dados
    $conn = conectaBanco();

    // Query para buscar os tatuadores (ajuste o nome da tabela e os campos de acordo com seu banco)
    $query = "SELECT id, nome FROM tatuadores ORDER BY nome ASC";
    $result = $conn->query($query);

    // Verifica se retornou algum resultado
    if ($result->num_rows > 0) {
        // Loop pelos resultados e imprime as opções do select
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . htmlspecialchars($row['id']) . "'>" . htmlspecialchars($row['nome']) . "</option>";
        }
    } else {
        // Caso não haja tatuadores
        echo "<option value=''>Nenhum tatuador encontrado</option>";
    }
    
} catch (Exception $e) {
    // Em caso de erro na conexão ou na query, exibe uma opção com erro
    echo "<option value=''>Erro ao carregar tatuadores</option>";
}


