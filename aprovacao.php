<?php
// Conexão com o banco de dados
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

// Cria a conexão
$conexao = mysqli_connect($servername, $username, $password, $dbname);


// Verificar conexão
if ($conexao->connect_error) {
    die("Conexão falhou: " . $conexao->connect_error);
}

// Função para aprovar/reprovar
if (isset($_POST['action']) && isset($_POST['id'])) {
    $id = $_POST['id'];
    $action = $_POST['action'];

    if ($action === 'aprovar') {
        $sql = "UPDATE solicitacao SET status='aprovado' WHERE id=$id";
    } else if ($action === 'reprovar') {
        $sql = "UPDATE solicitacao SET status='reprovado' WHERE id=$id";
    }

    if ($conexao->query($sql) === TRUE) {
        echo "Solicitação atualizada com sucesso!";
    } else {
        echo "Erro ao atualizar solicitação: " . $conexao->error;
    }
}

// Recuperar solicitações do banco de dados
$sql = "SELECT id, descricao, status FROM solicitacao";
$result = $conexao->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Aprovação de Solicitações</title>
    <link rel="stylesheet" href="css/styles.css">
</head>
<style>
    body {
        font-family: Arial, sans-serif;
    }

    .navbar {
        background-color: #333;
        overflow: hidden;
    }

    .nav-links a {
        float: left;
        display: block;
        color: #f2f2f2;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
    }

    .nav-links a:hover {
        background-color: #ddd;
        color: black;
    }

    .approval-container {
        margin: 20px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    table,
    th,
    td {
        border: 1px solid black;
    }

    th,
    td {
        padding: 15px;
        text-align: left;
    }

    th {
        background-color: #f2f2f2;
    }
</style>

<body>
    <header class="navbar">
        <nav class="nav-links">
            <a href="index.html">Home</a>
            <a href="projetos.html">Projetos</a>
            <a href="publicacoes.html">Publicações</a>
            <a href="gerador.html">Geradores</a>
            <a href="projetos/controleFinanceiro/login.php">Controle Financeiro</a>
            <a href="kanban.html">Kanban</a>
            <a href="jogos.html">Jogos</a>
            <a href="https://matheusavelar.github.io/">Currículo</a>
            <a href="apropriacao.php">Apropriação de Horas</a>
            <a href="receitas.html">Receitas</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <h1>Aprovação de Solicitações</h1>

    <div class="approval-container">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Descrição</th>
                    <th>Status</th>
                    <th>Ação</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    // Exibir cada solicitação
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td>" . $row["id"] . "</td>";
                        echo "<td>" . $row["descricao"] . "</td>";
                        echo "<td>" . $row["status"] . "</td>";
                        echo "<td>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='id' value='" . $row["id"] . "'>
                                <button type='submit' name='action' value='aprovar'>Aprovar</button>
                            </form>
                            <form method='post' style='display:inline;'>
                                <input type='hidden' name='id' value='" . $row["id"] . "'>
                                <button type='submit' name='action' value='reprovar'>Reprovar</button>
                            </form>
                          </td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='3'>Nenhuma solicitação pendente</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>

</html>

<?php
// Fechar conexão
$conexao->close();
?>