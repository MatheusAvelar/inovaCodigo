<?php
session_start();
include 'php/verificar_perfil.php';

if ($_SESSION['perfil_id'] != 2) {
    header("Location: agendamento.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conflitos Agendamentos</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdn.sheetjs.com/xlsx-latest/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <a href="https://inovacodigo.com.br/projetos/estudioThais/agendamento.php">
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
                <?php if ($perfil_id == 2) : ?>
                    <li><a href="criar_acesso.php">Criar Acesso</a></li>
                    <li><a href="usuarios_estudio.php">Usuários</a></li>
                <?php endif; ?>
                <li><a href="termos_enviados.php">Termos Enviados</a></li>
                <li><a href="agendamento.php">Agendamento</a></li>
                <li class="dropdown">
                    <a href="#">Opções</a>
                    <ul class="dropdown-content">
                        <li><a href="opcao1.php">Opção 1</a></li>
                        <li><a href="opcao2.php">Opção 2</a></li>
                        <li><a href="opcao3.php">Opção 3</a></li>
                    </ul>
                </li>
                <li><a href="php/logout.php">Sair</a></li>
            </ul> 
        </nav>
        <br>
        <h2>Conflitos Agendamentos</h2>
        <div class="grid">
            <div class="maca">
                <table>
                    <tbody>
                        <?php
                        include 'php/teste_conflitos.php';
                        ?>
                    </tbody>
                </table>
            </div>            
        </div>
    </div>
</body>
</html>
