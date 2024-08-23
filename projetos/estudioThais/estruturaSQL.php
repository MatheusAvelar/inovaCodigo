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
        .data-container {
            display: none;
            margin-top: 20px;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 10px;
            background-color: #f9f9f9;
        }
        .data-container table {
            border: none;
        }
        .data-container table th, .data-container table td {
            border-bottom: 1px solid #ddd;
        }
        .data-container table th {
            background-color: #f4f4f4;
        }
    </style>
    <script>
        function toggleData(tabela) {
            var container = document.getElementById("data-" + tabela);
            if (container.style.display === "none") {
                container.style.display = "block";
            } else {
                container.style.display = "none";
            }
        }
    </script>
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

        // Adiciona botão e contêiner para visualizar dados
        echo "<button onclick=\"toggleData('$tabela')\">Visualizar Dados</button>";
        echo "<div id=\"data-$tabela\" class=\"data-container\">";

        // Consulta para obter os dados da tabela
        $sql_dados = "SELECT * FROM $tabela";
        $result_dados = $conn->query($sql_dados);
        
        if ($result_dados->num_rows > 0) {
            echo "<table>
                    <tr>";
            // Exibir cabeçalhos de colunas
            $field_info = $result_dados->fetch_fields();
            foreach ($field_info as $val) {
                echo "<th>" . htmlspecialchars($val->name) . "</th>";
            }
            echo "</tr>";
            // Exibir dados
            while ($row_dado = $result_dados->fetch_assoc()) {
                echo "<tr>";
                foreach ($row_dado as $dado) {
                    echo "<td>" . htmlspecialchars($dado) . "</td>";
                }
                echo "</tr>";
            }
            echo "</table>";
        } else {
            echo "<p>Nenhum dado encontrado na tabela $tabela.</p>";
        }
        echo "</div>";
    }
} else {
    echo "<p>Nenhuma tabela encontrada no banco de dados $dbname.</p>";
}

$conn->close();
?>

</body>
</html>
