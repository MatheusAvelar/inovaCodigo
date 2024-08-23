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
        h1 {
            color: #007BFF;
        }
        h2 {
            color: #0056b3;
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
        tr:hover {
            background-color: #f1f1f1;
        }
        p {
            color: #666;
        }
    </style>
</head>
<body>

<?php
if ($result_tabelas->num_rows > 0) {
    echo "<h1>Estrutura do Banco de Dados: $dbname</h1>";
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
