<?php
// Configuração da conexão com o banco de dados
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

// Criando a conexão
$conn = new mysqli($servername, $username, $password, $dbname);

// Verificando a conexão
if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
}

// Obtendo a lista de tatuadores
$query = "SELECT 
            u.id, 
            u.ativo, 
            CONCAT(UCASE(LEFT(u.nome, 1)), LOWER(SUBSTRING(u.nome, 2)), ' ', 
                UCASE(LEFT(u.sobrenome, 1)), LOWER(SUBSTRING(u.sobrenome, 2))) AS nome, 
            u.email, 
            p.nome AS perfil_nome
          FROM usuarioEstudio AS u
          JOIN perfis AS p ON u.perfil_id = p.id;";
$result = $conn->query($query);

// Obter a contagem total de registros
$total_records = $result->num_rows;

if ($total_records > 0) {
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        $ativoStatus = $row['ativo'] == 1 ? 'Ativo' : 'Inativo';
        echo "<td>" . htmlspecialchars($ativoStatus) . "</td>";
        echo "<td>" . htmlspecialchars($row['nome']) . "</td>";
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

