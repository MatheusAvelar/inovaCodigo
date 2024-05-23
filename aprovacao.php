<?php
session_start();

// Verifica se o usuário está logado, se não, redireciona para a página de login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

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

// Função para atualizar todos os status para 'pendente'
if (isset($_POST['update_all_to_pending'])) {
    $sql = "UPDATE solicitacao SET status='pendente'";
    if ($conexao->query($sql) === TRUE) {
        echo "Todas as solicitações foram atualizadas para pendente!";
    } else {
        echo "Erro ao atualizar solicitações: " . $conexao->error;
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
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/ico.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" />
    <title>Inova Código - Aprovação de Solicitações</title>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1516963750502427" crossorigin="anonymous"></script>
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
            border-collapse: collapse;
            width: 100%;
        }

        th, td {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }

        input[type="time"],
        input[type="date"] {
            width: 100%;
            padding: 8px;
            margin: 4px 0;
            box-sizing: border-box;
        }

        form input,
        form textarea {
            width: 90%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 16px;
        }

        .delete-cell {
            text-align: center;
        }
    </style>
</head>

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

    <center>
        <h1>Aprovação de Solicitações</h1>
    </center>
    <div class="consulta-cep">
        <div class="approval-container">
            <form method="post" style="margin-bottom: 20px;">
                <button type="submit" name="update_all_to_pending">Atualizar Todos para Pendente</button>
            </form>
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
                            $status = $row["status"];
                            $displayStatus = ($status === 'aprovado') ? 'Aprovado' : (($status === 'reprovado') ? 'Reprovado' : 'Pendente');

                            echo "<tr>";
                            echo "<td>" . $row["id"] . "</td>";
                            echo "<td>" . $row["descricao"] . "</td>";
                            echo "<td>" . $displayStatus . "</td>";
                            echo "<td>";
                            if ($status === 'pendente') {
                                echo "<form method='post' style='display:inline;'>
                                    <input type='hidden' name='id' value='" . $row["id"] . "'>
                                    <button type='submit' name='action' value='aprovar'>Aprovar</button>
                                </form>
                                <form method='post' style='display:inline;'>
                                    <input type='hidden' name='id' value='" . $row["id"] . "'>
                                    <button type='submit' name='action' value='reprovar'>Reprovar</button>
                                </form>";
                            }
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='4'>Nenhuma solicitação pendente</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>

</body>

</html>

<?php
// Fechar conexão
$conexao->close();
?>
