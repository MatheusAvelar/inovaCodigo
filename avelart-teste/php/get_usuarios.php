<?php
include 'utils.php';

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

// Obtendo a lista de tatuadores
$query = "SELECT u.id, u.ativo, u.nome, u.sobrenome, u.email, p.nome AS perfil_nome
          FROM usuarioEstudio AS u
          JOIN perfis AS p ON u.perfil_id = p.id";
$result = $conn->query($query);

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

