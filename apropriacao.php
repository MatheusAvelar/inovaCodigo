<?php
session_start();

// Verifica se o usuário está logado, se não, redireciona para a página de login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
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
    <title>Inova Código - Apropriação de Horas - Controle de Demandas</title>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1516963750502427" crossorigin="anonymous"></script>
    <style>
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

        input[type="time"] {
            width: 100px;
        }
        input[type="date"] {
            width: 120px;
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
            <!--<a href="projetos/controleFinanceiro/login.php">Controle Financeiro</a>
            <a href="kanban.html">Kanban</a>
            <a href="jogos.html">Jogos</a>-->
            <a href="https://matheusavelar.github.io/">Currículo</a>
            <!--<a href="apropriacao.php">Apropriação de Horas</a>-->
            <a href="php/logout.php">Sair</a>
        </nav>
    </header>

    <center>
        <h1>Apropriação de Horas - Controle de Demandas</h1>
    </center>
    <div class="consulta-cep">
        <form id="hoursForm">
            <table>
                <tr>
                    <th>Data</th>
                    <th>Demanda</th>
                    <th>Hora Início</th>
                    <th>Hora Fim</th>
                    <th>Total</th>
                </tr>
                <tr>
                    <td><input type="date" id="date" name="date" onchange="updateTotalHours()"></td>
                    <td><input type="text" id="task" name="task"></td>
                    <td><input type="time" id="startTime" name="startTime" onchange="updateTotalHours()"></td>
                    <td><input type="time" id="endTime" name="endTime" onchange="updateTotalHours()"></td>
                    <td id="total">00:00</td>
                </tr>
            </table>
            <br>
            <button type="button" onclick="submitHours()">Registrar Horas</button>
        </form>
        <div id="response"></div>
        <h1>Relatório de Horas</h1>
        <form id="filterForm">
            <label for="filterStartDate">Data Inicial:</label>
            <input type="date" id="filterStartDate" name="filterStartDate" onchange="applyFilters()">

            <label for="filterEndDate">Data Final:</label>
            <input type="date" id="filterEndDate" name="filterEndDate" onchange="applyFilters()">

            <label for="filterTask">Filtrar por Demanda:</label>
            <input type="text" id="filterTask" name="filterTask" oninput="applyFilters()">
        </form>
        <div id="relatorio"></div> <!-- Local onde o relatório será exibido -->
    </div>

    <script>
        function submitHours() {
            var date = document.getElementById("date").value;
            var startTimeStr = document.getElementById("startTime").value;
            var endTimeStr = document.getElementById("endTime").value;
            var task = document.getElementById("task").value;

            if (date.trim() === '' || startTimeStr.trim() === '' || endTimeStr.trim() === '' || task.trim() === '') {
                alert("Por favor, preencha todos os campos.");
                return;
            }

            var startTime = new Date("1970-01-01T" + startTimeStr + "Z");
            var endTime = new Date("1970-01-01T" + endTimeStr + "Z");

            var totalHours = calculateTotalHours(startTime, endTime);

            var xhr = new XMLHttpRequest();
            xhr.open("POST", "php/save_hours.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("response").innerHTML = xhr.responseText;
                    // Verifica se a resposta do servidor indica sucesso
                    if (xhr.responseText.trim() === "success") {
                        // Se for bem-sucedido, exibe uma mensagem de sucesso
                        alert("Horas registradas com sucesso!");
                        // Limpa o formulário após o sucesso, se necessário
                        document.getElementById("date").value = "";
                        document.getElementById("startTime").value = "";
                        document.getElementById("endTime").value = "";
                        document.getElementById("task").value = "";
                        // Recarrega o relatório
                        carregarRelatorio();
                    }
                }
            };

            // Constrói os dados a serem enviados
            var dataToSend = "task=" + task + "&hours=" + totalHours + "&date=" + date +
                "&startTime=" + startTimeStr + "&endTime=" + endTimeStr;

            xhr.send(dataToSend);
        }

        function calculateTotalHours(startTime, endTime) {
            var milliseconds = endTime - startTime;
            var totalSeconds = milliseconds / 1000;
            var hours = Math.floor(totalSeconds / 3600);
            var minutes = Math.floor((totalSeconds % 3600) / 60);
            hours = String(hours).padStart(2, '0');
            minutes = String(minutes).padStart(2, '0');
            return hours + ":" + minutes;
        }

        function updateTotalHours() {
            var startTimeStr = document.getElementById("startTime").value;
            var endTimeStr = document.getElementById("endTime").value;

            if (!startTimeStr || !endTimeStr) {
                document.getElementById("total").textContent = "00:00";
                return;
            }

            var startTime = new Date("1970-01-01T" + startTimeStr + "Z");
            var endTime = new Date("1970-01-01T" + endTimeStr + "Z");

            var totalHours = calculateTotalHours(startTime, endTime);
            document.getElementById("total").textContent = totalHours;
        }

        function carregarRelatorio() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "php/relatorioApropriacao.php", true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("relatorio").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        // Carrega o relatório quando a página é carregada
        window.onload = function() {
            carregarRelatorio();
            setupCellEditing();
        };

        function applyFilters() {
            var filterStartDate = document.getElementById("filterStartDate").value;
            var filterEndDate = document.getElementById("filterEndDate").value;
            var filterTask = document.getElementById("filterTask").value;
            
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "php/relatorioApropriacao.php?filterStartDate=" + filterStartDate + "&filterEndDate=" + filterEndDate + "&filterTask=" + filterTask, true);
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    document.getElementById("relatorio").innerHTML = xhr.responseText;
                }
            };
            xhr.send();
        }

        function deleteRecord(recordId) {
            if (confirm("Tem certeza de que deseja excluir este registro?")) {
                var xhr = new XMLHttpRequest();
                xhr.open("POST", "php/delete_record.php", true);
                xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        alert(xhr.responseText); // Exibe a resposta do servidor
                        // Recarrega o relatório após a exclusão
                        applyFilters();
                    }
                };
                xhr.send("recordId=" + recordId);
            }
        }

        function setupCellEditing() {
            var cells = document.querySelectorAll('#relatorio td:not(:first-child)');
            cells.forEach(function(cell) {
                cell.addEventListener('dblclick', function() {
                    var oldValue = this.textContent;
                    this.setAttribute('contenteditable', 'true');
                    this.focus();

                    this.addEventListener('keydown', function(event) {
                        if (event.key === 'Enter') {
                            event.preventDefault();
                            this.blur();
                        }
                    });

                    this.addEventListener('blur', function() {
                        var newValue = this.textContent;
                        var rowId = this.parentElement.dataset.rowId;
                        var cellIndex = this.cellIndex; // Index of the cell in the row

                        if (newValue !== oldValue) {
                            updateCellValue(rowId, cellIndex, newValue);
                        }

                        this.removeAttribute('contenteditable');
                    });
                });
            });
        }

        function updateCellValue(rowId, cellIndex, newValue) {
            var xhr = new XMLHttpRequest();
            xhr.open("POST", "php/update_cell_value.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    console.log(xhr.responseText); // Exibe a resposta do servidor
                    // Aqui você pode atualizar a interface do usuário de acordo com a resposta do servidor, se necessário
                    applyFilters();
                }
            };
            xhr.send("rowId=" + rowId + "&cellIndex=" + cellIndex + "&newValue=" + encodeURIComponent(newValue));
        }
    </script>
</body>
</html>