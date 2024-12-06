<?php
session_start();
include 'php/utils.php';

try {
    $conn = conectaBanco();
} catch (Exception $e) {
    die("Erro: " . $e->getMessage());
}

// Verifica se há mensagem de status na sessão
$status = isset($_SESSION['status']) ? $_SESSION['status'] : null;
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;

// Limpa as mensagens de status da sessão após exibir
unset($_SESSION['status'], $_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Agendamentos</title>
    <link rel="icon" href="img/ico.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css?v=1.0">
    <script src="https://cdn.sheetjs.com/xlsx-latest/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        #menu ul li { 
            display: inline-block; 
        }
        /* Estilo básico do dropdown */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        /* Estilo para a engrenagem */
        .settings-icon {
            font-size: 18px;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo-container">
            <a href="https://avelart-teste.inovacodigo.com.br/agendamento.php">
                <img src="img/tatto.jpeg" alt="Logo do Estúdio" class="logo">
            </a>
        </div>
        <div class="welcome-message">
            Bem Vindo, <?= htmlspecialchars($_SESSION['usuario_nome']) ?>!
        </div>
    </header>

    <div class="container">
        <nav id="menu"> 
            <ul>
                <li><a href="termos_enviados.php">Termos Preenchidos</a></li>
                <li><a href="horarios_agendados.php">Horários Agendados</a></li>
                <?php if ($_SESSION['perfil_id'] == 2) : ?>
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <i class="fas fa-cog settings-icon"></i>
                        </a>
                        <div class="dropdown-content">
                            <a href="criar_acesso.php">Criar Acesso</a>
                            <a href="usuarios_estudio.php">Usuários</a>
                            <a href="visao_conflitos.php">Conflitos</a>
                        </div>
                    </li>
                <?php endif; ?>
                <li><a href="php/logout.php">Sair</a></li>
            </ul> 
        </nav>
        <br>
        <div id="message-container">
            <?php if (isset($status) && isset($message)) : ?>
                <div class="message <?= $status ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>
        </div>

        <h2>Relatório</h2>
    
        <div class="grid">
            <div class="maca">
                <form method="POST" action="php/get_dados_relatorio.php">
                    <label for="inicio">Data de Início:</label>
                    <input type="date" id="inicio" name="inicio"> 

                    <label for="fim">Data de Fim:</label>
                    <input type="date" id="fim" name="fim">

                    <label for="filter_tatuador">Tatuador:</label>
                    <select id="filter_tatuador" name="filter_tatuador">
                        <option value="">Todos os Tatuadores</option>
                        <?php
                        // Carregar a lista de tatuadores
                        try {
                            $query = "SELECT id, nome FROM usuarioEstudio ORDER BY nome ASC";
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
                        ?>
                    </select>

                    <label for="tipo_relatorio">Tipo de Relatório:</label>
                    <select id="tipo_relatorio" name="tipo_relatorio">
                        <option value="faturado">Total Faturado</option>
                        <option value="recebido_estudio">Total Recebido pelo Estúdio</option>
                    </select>

                    <button type="submit">Gerar Relatório</button>
                </form>
                <div>exibição do relatorio aqui</div>
            </div>
        </div>
    </div>
</body>
<!-- Div para exibir os resultados -->
    <div id="resultado" style="margin-top: 20px; border: 1px solid #ddd; padding: 10px; display: none;">
        <h2>Resultado do Relatório</h2>
        <div id="dados-relatorio"></div>
    </div>

    <script>
        // Evento de envio do formulário
        $('#form-relatorio').on('submit', function (e) {
            e.preventDefault();

            // Obter o tipo de relatório selecionado
            const tipoRelatorio = $('#tipo_relatorio').val();

            if (!tipoRelatorio) {
                alert("Por favor, selecione um tipo de relatório.");
                return;
            }

            // Enviar via AJAX para o servidor
            $.ajax({
                url: 'get_dados_relatorio.php', // Arquivo PHP para processar
                type: 'POST',
                data: { tipo_relatorio: tipoRelatorio },
                success: function (response) {
                    $('#dados-relatorio').html(response); // Atualizar a div com os dados
                    $('#resultado').show(); // Exibir a div de resultados
                },
                error: function () {
                    alert("Erro ao gerar relatório. Tente novamente.");
                }
            });
        });
    </script>
</html>
