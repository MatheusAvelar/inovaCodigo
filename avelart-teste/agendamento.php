<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $errors = [];

    // Capturar os dados do formulário
    $form_data = [
        'maca' => $_POST['maca'] ?? '',
        'date1' => $_POST['date1'] ?? '',
        'start-time1' => $_POST['start-time1'] ?? '',
        'end-time1' => $_POST['end-time1'] ?? '',
        'cliente' => $_POST['cliente'] ?? '',
        'telefone' => $_POST['telefone'] ?? '',
        'email' => $_POST['email'] ?? '',
        'estilo' => $_POST['estilo'] ?? '',
        'tamanho' => $_POST['tamanho'] ?? '',
        'valor' => $_POST['valor'] ?? '',
        'pagamento' => $_POST['pagamento'] ?? '',
        'sinal_pago' => $_POST['sinal_pago'] ?? '',
        'descricao' => $_POST['descricao'] ?? '',
    ];

    // Validação dos campos
    if (empty($form_data['maca'])) {
        $errors['maca'] = "Selecione uma maca.";
    }
    if (empty($form_data['date1'])) {
        $errors['date1'] = "Informe a data.";
    }
    if (empty($form_data['start-time1'])) {
        $errors['start-time1'] = "Informe o horário inicial.";
    }
    if (empty($form_data['end-time1'])) {
        $errors['end-time1'] = "Informe o horário final.";
    }
    if (empty($form_data['cliente'])) {
        $errors['cliente'] = "Informe o nome do cliente.";
    }
    if (empty($form_data['valor'])) {
        $errors['valor'] = "Informe o valor.";
    }
    if (empty($form_data['pagamento'])) {
        $errors['pagamento'] = "Selecione a forma de pagamento.";
    }
    if (empty($form_data['sinal_pago'])) {
        $errors['sinal_pago'] = "Informe se o sinal foi pago.";
    }

    // Se houver erros, salvar os dados na sessão e redirecionar
    if (!empty($errors)) {
        $_SESSION['form_data'] = $form_data;
        $_SESSION['errors'] = $errors;
        header("Location: formulario.php");
        exit;
    }

    // Processar os dados (salvar no banco de dados, etc.)
    // Aqui você pode adicionar o código para salvar no banco de dados
    echo "Formulário enviado com sucesso!";
    exit;
}

// Recuperar os dados da sessão, se existirem
$form_data = $_SESSION['form_data'] ?? [];
$errors = $_SESSION['errors'] ?? [];
$status = isset($_SESSION['status']) ? $_SESSION['status'] : null;
$message = isset($_SESSION['message']) ? $_SESSION['message'] : null;

// Limpar os dados da sessão para evitar reutilização
unset($_SESSION['form_data']);
unset($_SESSION['errors']);
unset($_SESSION['status'], $_SESSION['message']);
?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agendamento de Macas</title>
    <link rel="icon" href="img/ico.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/style.css?v=1.0">
    <script src="https://cdn.sheetjs.com/xlsx-latest/xlsx.full.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.0/xlsx.full.min.js"></script>
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

        <h2>Agendamento de Macas</h2>

        <div class="grid">
            <div class="maca">
                <form id="form1" method="POST" action="php/agendar_maca.php" onsubmit="return validateForm()">
                    <label for="maca">Maca:</label>
                    <select id="maca" name="maca">
                        <option value="">Selecione a maca</option>
                        <option value="1" <?= isset($form_data['maca']) && $form_data['maca'] == '1' ? 'selected' : '' ?>>Maca 1</option>
                        <option value="2" <?= isset($form_data['maca']) && $form_data['maca'] == '2' ? 'selected' : '' ?>>Maca 2</option>
                        <option value="3" <?= isset($form_data['maca']) && $form_data['maca'] == '3' ? 'selected' : '' ?>>Maca 3</option>
                        <option value="4" <?= isset($form_data['maca']) && $form_data['maca'] == '4' ? 'selected' : '' ?>>Maca 4</option>
                        <option value="5" <?= isset($form_data['maca']) && $form_data['maca'] == '5' ? 'selected' : '' ?>>Sala de Atendimento Íntimo</option>
                    </select>
                    <div id="maca-error" class="error-message"><?= $errors['maca'] ?? '' ?></div>

                    <label for="date1">Data:</label>
                    <input type="date" id="date1" name="date1" value="<?= htmlspecialchars($form_data['date1'] ?? '') ?>">
                    <div id="date1-error" class="error-message"><?= $errors['date1'] ?? '' ?></div>

                    <label for="start-time1">Horário Inicial:</label>
                    <input type="time" id="start-time1" name="start-time1" value="<?= htmlspecialchars($form_data['start-time1'] ?? '') ?>">
                    <div id="start-time1-error" class="error-message"><?= $errors['start-time1'] ?? '' ?></div>

                    <label for="end-time1">Horário Final:</label>
                    <input type="time" id="end-time1" name="end-time1" value="<?= htmlspecialchars($form_data['end-time1'] ?? '') ?>">
                    <div id="end-time1-error" class="error-message"><?= $errors['end-time1'] ?? '' ?></div>

                    <label for="cliente">Nome do Cliente:</label>
                    <input type="text" id="cliente" name="cliente" value="<?= htmlspecialchars($form_data['cliente'] ?? '') ?>">
                    <div id="name-error" class="error-message"><?= $errors['cliente'] ?? '' ?></div>

                    <label for="telefone">Telefone Celular:</label>
                    <input type="tel" id="telefone" name="telefone" value="<?= htmlspecialchars($form_data['telefone'] ?? '') ?>">

                    <label for="email">E-mail do Cliente:</label>
                    <input type="email" id="email" name="email" value="<?= htmlspecialchars($form_data['email'] ?? '') ?>">
                    <div id="email-error" class="error-message"></div>

                    <label for="estilo">Estilo:</label>
                    <input type="text" id="estilo" name="estilo" value="<?= htmlspecialchars($form_data['estilo'] ?? '') ?>">

                    <label for="tamanho">Tamanho (cm):</label>
                    <input type="text" id="tamanho" name="tamanho" value="<?= htmlspecialchars($form_data['tamanho'] ?? '') ?>">

                    <label for="valor">Valor:</label>
                    <input type="text" id="valor" name="valor" value="<?= htmlspecialchars($form_data['valor'] ?? '') ?>">
                    <div id="valor-error" class="error-message"><?= $errors['valor'] ?? '' ?></div>

                    <label for="pagamento">Forma de Pagamento:</label>
                    <select id="pagamento" name="pagamento">
                        <option value="">Selecione a forma de pagamento</option>
                        <option value="Dinheiro" <?= isset($form_data['pagamento']) && $form_data['pagamento'] == 'Dinheiro' ? 'selected' : '' ?>>Dinheiro</option>
                        <option value="Cartão" <?= isset($form_data['pagamento']) && $form_data['pagamento'] == 'Cartão' ? 'selected' : '' ?>>Cartão</option>
                        <option value="Cartão Estúdio" <?= isset($form_data['pagamento']) && $form_data['pagamento'] == 'Cartão Estúdio' ? 'selected' : '' ?>>Cartão Estúdio</option>
                        <option value="Pix" <?= isset($form_data['pagamento']) && $form_data['pagamento'] == 'Pix' ? 'selected' : '' ?>>Pix</option>
                    </select>
                    <div id="pagamento-error" class="error-message"><?= $errors['pagamento'] ?? '' ?></div>

                    <label for="sinal_pago">Sinal pago?</label>
                    <select id="sinal_pago" name="sinal_pago">
                        <option value="">Selecione uma opção</option>
                        <option value="Sim" <?= isset($form_data['sinal_pago']) && $form_data['sinal_pago'] == 'Sim' ? 'selected' : '' ?>>Sim</option>
                        <option value="Não" <?= isset($form_data['sinal_pago']) && $form_data['sinal_pago'] == 'Não' ? 'selected' : '' ?>>Não</option>
                    </select>
                    <div id="sinal_pago-error" class="error-message"><?= $errors['sinal_pago'] ?? '' ?></div>

                    <label for="descricao">Descrição:</label>
                    <input type="text" id="descricao" name="descricao" value="<?= htmlspecialchars($form_data['descricao'] ?? '') ?>">

                    <button type="submit">Agendar</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var emailInput = document.getElementById('email');
            var emailError = document.getElementById('email-error');

            emailInput.addEventListener('input', function() {
                if (emailInput.validity.valid) {
                    emailError.textContent = ''; // Limpa a mensagem de erro se válido
                    emailError.style.display = 'none';
                } else {
                    emailError.textContent = 'Por favor, insira um e-mail válido para o cliente receber as informações do seu agendamento.';
                    emailError.style.display = 'block';
                }
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const valorInput = document.getElementById('valor');
            
            valorInput.addEventListener('input', function () {
                let value = valorInput.value.replace(/\D/g, '');
                value = (value / 100).toFixed(2);
                value = value.replace('.', ',');
                valorInput.value = 'R$ ' + value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            });
        });

        function validateForm() {
            let isValid = true;

            // Clear previous error messages
            document.querySelectorAll('.error-message').forEach(function (element) {
                element.innerText = '';
            });
            
            // Get form values
            const maca = document.getElementById('maca').value;
            const date = document.getElementById('date1').value;
            const startTime = document.getElementById('start-time1').value;
            const endTime = document.getElementById('end-time1').value;
            
            // Remove mask and convert to float
            const valorInput = document.getElementById('valor').value;
            console.log('Valor input:', valorInput); // Debug: Log the masked input
            const valor = parseFloat(valorInput.replace('R$ ', '').replace(/\./g, '').replace(',', '.'));
            console.log('Valor parsed:', valor); // Debug: Log the parsed value

            // Validate maca
            if (maca === '') {
                isValid = false;
                document.getElementById('maca-error').innerText = 'Selecione uma maca.';
            }

            // Validate date
            if (date === '') {
                isValid = false;
                document.getElementById('date1-error').innerText = 'A data é obrigatória.';
            }

            // Validate start time
            if (startTime === '') {
                isValid = false;
                document.getElementById('start-time1-error').innerText = 'O horário inicial é obrigatório.';
            }

            // Validate end time
            if (endTime === '') {
                isValid = false;
                document.getElementById('end-time1-error').innerText = 'O horário final é obrigatório.';
            }

            // Validate that end time is after start time
            if (startTime && endTime && startTime >= endTime) {
                isValid = false;
                document.getElementById('end-time1-error').innerText = 'O horário final deve ser maior que o horário inicial.';
            }

            // Validate valor
            if (valor <= 0) {
                isValid = false;
                document.getElementById('valor-error').innerText = 'O valor deve ser maior que R$ 0.';
            }

            if (!isValidDateFormat(date)) {
                isValid = false;
                document.getElementById('date1-error').innerText = 'Formato de data inválido. Use YYYY-MM-DD.';
            }

            return isValid;
        }

        function isValidDateFormat(date) {
            const datePattern = /^\d{4}-\d{2}-\d{2}$/;
            return datePattern.test(date);
        }

        document.addEventListener('DOMContentLoaded', function () {
            const status = sessionStorage.getItem('status');
            const message = sessionStorage.getItem('message');

            if (status && message) {
                const messageContainer = document.getElementById('message-container');
                const messageElement = document.createElement('div');
                messageElement.className = 'message ' + status;
                messageElement.innerHTML = message;

                messageContainer.appendChild(messageElement);

                // Limpa as mensagens após exibi-las
                sessionStorage.removeItem('status');
                sessionStorage.removeItem('message');
            }
        });
    </script>
</body>

</html>
