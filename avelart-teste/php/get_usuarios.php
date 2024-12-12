<?php 
include 'utils.php';

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

// Definir o número de registros por página
$recordsPerPage = 50;

// Obter o número total de registros
$totalQuery = "SELECT COUNT(*) AS total FROM usuarioEstudio AS u";
$totalResult = $conn->query($totalQuery);
$totalRow = $totalResult->fetch_assoc();
$totalRecords = $totalRow['total'];

// Calcular o número total de páginas
$totalPages = ceil($totalRecords / $recordsPerPage);

// Obter a página atual, se não estiver definida, usar a página 1
$currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;

// Calcular o índice de início para a consulta SQL (baseado na página atual)
$offset = ($currentPage - 1) * $recordsPerPage;

// Consulta para obter os dados da página atual
$query = "SELECT u.id, u.ativo, u.nome, u.sobrenome, u.email, p.nome AS perfil_nome
          FROM usuarioEstudio AS u
          JOIN perfis AS p ON u.perfil_id = p.id
          LIMIT $offset, $recordsPerPage";
$result = $conn->query($query);

// Obtém o número de registros na página atual
$totalRecordsCurrentPage = $result->num_rows;

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['id']) . "</td>";
        $ativoStatus = $row['ativo'] == 1 ? 'Ativo' : 'Inativo';
        echo "<td>" . htmlspecialchars($ativoStatus) . "</td>";
        echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
        echo "<td>" . htmlspecialchars($row['sobrenome']) . "</td>";
        echo "<td>" . htmlspecialchars($row['email']) . "</td>";
        echo "<td>" . htmlspecialchars($row['perfil_nome']) . "</td>";
        echo "<td>
            <a href='editar_usuario.php?id=" . htmlspecialchars($row['id']) . "' title='Editar'>
                <i class='fas fa-edit'></i>
            </a>
            <a href='php/deletar_usuario.php?id=" . htmlspecialchars($row['id']) . "' title='Deletar' onclick='return confirm(\"Tem certeza que deseja deletar este usuário?\");'>
                <i class='fas fa-trash-alt'></i>
            </a>
            </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6'>Nenhum usuário encontrado.</td></tr>";
}

// Fechando a conexão
$conn->close();
?>