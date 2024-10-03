<?php
$servername = "127.0.0.1:3306";
$username = "u221588236_root";
$password = "Camila@307";
$dbname = "u221588236_controle_finan";

// Conexão com o banco de dados
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

// Deletar uma linha
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete'])) {
    $tabela = $_POST['tabela'];
    $chave_primaria = $_POST['chave_primaria'];
    $valor_chave = $_POST['valor_chave'];

    $sql_delete = "DELETE FROM $tabela WHERE $chave_primaria = '$valor_chave'";
    $conn->query($sql_delete);
}

// Inserir uma nova linha
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['insert'])) {
    $tabela = $_POST['tabela'];
    $colunas = $_POST['colunas'];
    $valores = array_map(function ($valor) use ($conn) {
        return "'" . $conn->real_escape_string($valor) . "'";
    }, $_POST['valores']);

    $sql_insert = "INSERT INTO $tabela ($colunas) VALUES (" . implode(',', $valores) . ")";
    $conn->query($sql_insert);
}

// Executar um comando SQL personalizado
$sql_custom_result = '';
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['sql_command'])) {
    $sql_custom = $_POST['sql_custom'];
    if (!empty($sql_custom)) {
        $result = $conn->query($sql_custom);
        if ($result === TRUE) {
            $sql_custom_result = "Comando executado com sucesso!";
        } elseif ($result->num_rows > 0) {
            // Se houver um resultado (como em SELECT), exiba a tabela
            $sql_custom_result = "<table border='1'><tr>";
            $fields = $result->fetch_fields();
            foreach ($fields as $field) {
                $sql_custom_result .= "<th>{$field->name}</th>";
            }
            $sql_custom_result .= "</tr>";
            while ($row = $result->fetch_assoc()) {
                $sql_custom_result .= "<tr>";
                foreach ($row as $value) {
                    $sql_custom_result .= "<td>" . htmlspecialchars($value) . "</td>";
                }
                $sql_custom_result .= "</tr>";
            }
            $sql_custom_result .= "</table>";
        } else {
            $sql_custom_result = "Erro ao executar o comando: " . $conn->error;
        }
    }
}

// Consulta para obter todas as tabelas do banco de dados
$sql_tabelas = "SHOW TABLES";
$result_tabelas = $conn->query($sql_tabelas);

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estrutura do Banco de Dados</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1, h2 {
            color: #007BFF;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f4f4f4;
            color: #555;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

<h1>Estrutura do Banco de Dados: <?php echo $dbname; ?></h1>

<!-- Formulário para executar um comando SQL personalizado -->
<h2>Executar Comando SQL</h2>
<form method="POST">
    <textarea name="sql_custom" rows="5" cols="100" placeholder="Escreva seu comando SQL aqui..."></textarea><br>
    <button type="submit" name="sql_command">Executar Comando SQL</button>
</form>

<?php
// Exibir resultado do comando SQL personalizado
if (!empty($sql_custom_result)) {
    echo "<h3>Resultado:</h3>";
    echo $sql_custom_result;
}
?>

<?php
if ($result_tabelas->num_rows > 0) {
    while ($row_tabela = $result_tabelas->fetch_array()) {
        $tabela = $row_tabela[0];
        echo "<h2>Tabela: $tabela</h2>";
        
        // Consulta para obter as colunas da tabela atual
        $sql_colunas = "SHOW COLUMNS FROM $tabela";
        $result_colunas = $conn->query($sql_colunas);

        if ($result_colunas->num_rows > 0) {
            echo "<table>
                    <tr>
                        <th>Coluna</th>
                        <th>Tipo</th>
                        <th>Null</th>
                        <th>Chave</th>
                        <th>Valor Padrão</th>
                        <th>Extra</th>
                    </tr>";
            while ($row_coluna = $result_colunas->fetch_assoc()) {
                echo "<tr>
                        <td>" . htmlspecialchars($row_coluna['Field']) . "</td>
                        <td>" . htmlspecialchars($row_coluna['Type']) . "</td>
                        <td>" . htmlspecialchars($row_coluna['Null']) . "</td>
                        <td>" . htmlspecialchars($row_coluna['Key']) . "</td>
                        <td>" . htmlspecialchars($row_coluna['Default']) . "</td>
                        <td>" . htmlspecialchars($row_coluna['Extra']) . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Nenhuma coluna encontrada na tabela $tabela.</p>";
        }
    }
} else {
    echo "<p>Nenhuma tabela encontrada no banco de dados $dbname.</p>";
}

$conn->close();
?>

</body>
</html>
