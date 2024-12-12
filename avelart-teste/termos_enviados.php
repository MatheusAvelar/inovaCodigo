<?php
session_start();
include 'php/verificar_perfil.php';

// Verifica se há mensagem de status na sessão
$status = isset($_SESSION['status']) ? $_SESSION['status'] : null;
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;

// Limpa as mensagens de status da sessão após exibir
unset($_SESSION['status'], $_SESSION['message']);

$months = [
    "01" => "Janeiro",
    "02" => "Fevereiro",
    "03" => "Março",
    "04" => "Abril",
    "05" => "Maio",
    "06" => "Junho",
    "07" => "Julho",
    "08" => "Agosto",
    "09" => "Setembro",
    "10" => "Outubro",
    "11" => "Novembro",
    "12" => "Dezembro",
];
?>
<!DOCTYPE html>
<html lang="pt-BR"> 
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termos Preenchidos</title>
    <link rel="icon" href="img/ico.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
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

        #filter-actions-form {
            display: flex;
            flex-direction: column;
            gap: 20px;
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
        }

        #filters-container {
            display: flex;
            flex-wrap: wrap;
            gap: 15px;
            padding: 15px;
            background-color: #f8f9fa; 
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        #filters-container div {
            flex: 1 1 calc(33.333% - 15px);
            min-width: 200px;
        }

        #filters-container label {
            display: block;
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 5px;
            color: #333;
        }

        #filters-container select,
        #filters-container input {
            width: 96%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            font-size: 14px;
            background-color: #fff;
            transition: border-color 0.2s ease-in-out;
        }

        #filters-container select:hover,
        #filters-container input:hover {
            border-color: #ff6f61;
        }

        #actions-container {
            justify-content: flex-end;
            gap: 10px;
            padding: 15px;
            background-color: #f4f4f4;
            border: 1px solid #ddd;
            border-radius: 8px;
            display: flex;
        }

        #actions-container .button {
            padding: 12px 20px;
            font-size: 16px;
            font-weight: bold;
            color: #fff;
            background-color: #fec66f;
            border: none; 
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
        }

        #actions-container .button:hover {
            background-color: #f4ab36;
        }

        #actions-container .button i {
            margin-right: 5px;
        }

        @media (max-width: 768px) {
            #filters-container {
                flex-direction: column;
            }

            #filters-container div {
                flex: 1 1 100%;
            }

            #actions-container {
                justify-content: center;
            }

            #actions-container .button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <a href="https://avelart.inovacodigo.com.br/index.php">
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
                <li><a href="agendamento.php">Agendamento</a></li>
                <li><a href="horarios_agendados.php">Horários Agendados</a></li>
                <?php if ($perfil_id == 2) : ?>
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
            <?php if ($status && $message) : ?>
                <div class="message <?= $status ?>">
                    <?= htmlspecialchars($message) ?>
                </div>
            <?php endif; ?>
        </div>
        <h2>Termos Preenchidos</h2>
            <form id="filter-actions-form" method="GET" action="termos_enviados.php">
                <div id="filters-container">
                    <div>
                        <label for="filter-maca">Cliente:</label>
                        <input type="text" name="cliente_nome" id="cliente_nome" placeholder="Nome do Cliente" value="<?= htmlspecialchars(isset($_GET['cliente_nome']) ? $_GET['cliente_nome'] : '') ?>">
                    </div>
                </div>

                <div id="actions-container">
                    <button type="submit" class="button">
                        <i class="fas fa-search"></i> Filtrar
                    </button>
    
                    <?php if ($perfil_id == 2) : ?>
                        <!--<button type="button" class="button" id="export-button">
                            <i class="fa-solid fa-file-csv"></i> Exportar
                        </button>-->
                    <?php endif; ?>
                </div>
            </form>
        <div class="grid">
            <div class="maca">
                <div style="overflow-x: auto;">
                    <table>
                        <thead>
                            <tr>
                                <th>Cliente</th>
                                <th>Email</th>
                                <th>Data Envio</th>
                                <th>Ação</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            // Inclua o arquivo com a lógica para buscar os Termos Preenchidos
                            include 'php/fetch_termos_enviados.php';
                            ?>
                        </tbody>
                    </table>
                </div><br>
                <!-- Exibe a lista de páginas -->
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=1&cliente_nome=<?php echo $cliente_nome; ?>" class="page-link"><i class="fas fa-angles-left"></i></a>
                        <a href="?page=<?php echo $currentPage - 1; ?>&cliente_nome=<?php echo $cliente_nome; ?>" class="page-link"><i class="fas fa-angle-left"></i></a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>&cliente_nome=<?php echo $cliente_nome; ?>" class="page-link <?php echo $i == $currentPage ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?php echo $currentPage + 1; ?>&cliente_nome=<?php echo $cliente_nome; ?>" class="page-link"><i class="fas fa-angle-right"></i></a>
                        <a href="?page=<?php echo $totalPages; ?>&cliente_nome=<?php echo $cliente_nome; ?>" class="page-link"><i class="fas fa-angles-right"></i></a>
                    <?php endif; ?>
                </div>
                <br>
                <!-- Exibe a contagem de registros -->
                <div class="record-count">
                    <p>Total de Registros: <?php echo $totalRecords; ?></p>
                    <p>Registros nesta página: <?php echo $totalRecordsCurrentPage; ?></p>
                </div>
            </div>
        </div>            
    </div>

</body>
</html>
