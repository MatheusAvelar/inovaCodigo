<?php
session_start();
include 'php/verificar_perfil.php';

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
    </style>
</head>
<body>
    <header>
        <div class="logo-container">
            <a href="https://avelart.inovacodigo.com.br/agendamento.php">
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
        <div class="grid">
            <div class="maca">
            <form method="GET" action="">
                <label for="cliente_nome">Filtrar por Cliente:</label>
                <input type="text" name="cliente_nome" id="cliente_nome" placeholder="Nome do Cliente" value="<?= htmlspecialchars(isset($_GET['cliente_nome']) ? $_GET['cliente_nome'] : '') ?>">
                <button type="submit">Filtrar</button>
            </form>
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
                    <p>Total de Registros: <?php echo $total_records; ?></p>
                    <p>Registros nesta página: <?php echo $offset; ?></p>
                </div>
            </div>            
        </div>
    </div>

</body>
</html>
