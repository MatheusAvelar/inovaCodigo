<?php
session_start();
include 'php/verificar_perfil.php';

if ($_SESSION['perfil_id'] != 2) {
    header("Location: agendamento.php");
    exit();
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
    <title>Visualizar Usuários</title>
    <link rel="icon" href="img/ico.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <script src="https://cdn.sheetjs.com/xlsx-latest/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
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
                <li><a href="termos_enviados.php">Termos Preenchidos</a></li>
                <li><a href="agendamento.php">Agendamento</a></li>
                <li><a href="horarios_agendados.php">Horários Agendados</a></li>
                <?php if ($perfil_id == 2) : ?>
                    <li class="dropdown">
                        <a href="javascript:void(0)">
                            <i class="fas fa-cog settings-icon"></i>
                        </a>
                        <div class="dropdown-content">
                            <a href="criar_acesso.php">Criar Acesso</a>
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
        <h2>Usuários do Sistema</h2>
        <div class="grid">
            <div class="maca">
                <div style="overflow-x: auto;">
                <table>
                    <thead>
                        <tr>
                            <th>Ativo</th>
                            <th>Nome</th>
                            <th>Email</th>
                            <th>Perfil</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include 'php/get_usuarios.php';
                        ?>
                    </tbody>
                </table>
                </div><br>
                <!-- Exibe a lista de páginas -->
                <div class="pagination">
                    <?php if ($currentPage > 1): ?>
                        <a href="?page=1" class="page-link"><i class="fas fa-angles-left"></i></a>
                        <a href="?page=<?php echo $currentPage - 1; ?>" class="page-link"><i class="fas fa-angle-left"></i></a>
                    <?php endif; ?>

                    <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                        <a href="?page=<?php echo $i; ?>" class="page-link <?php echo $i == $currentPage ? 'active' : ''; ?>">
                            <?php echo $i; ?>
                        </a>
                    <?php endfor; ?>

                    <?php if ($currentPage < $totalPages): ?>
                        <a href="?page=<?php echo $currentPage + 1; ?>" class="page-link"><i class="fas fa-angle-right"></i></a>
                        <a href="?page=<?php echo $totalPages; ?>" class="page-link"><i class="fas fa-angles-right"></i></a>
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
