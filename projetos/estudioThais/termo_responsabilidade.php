<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termo de Autorização</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <div class="logo-container">
            <a href="https://inovacodigo.com.br/projetos/estudioThais/agendamento.php">
                <img src="img/tatto.jpeg" alt="Logo do Estúdio" class="logo">
            </a>
        </div>
    </header>
    
    <div class="container">
        <div id="message-container">
            <?php if (isset($status) && isset($message)) : ?>
                <div class="message <?= $status ?>">
                    <?= $message ?>
                </div>
            <?php endif; ?>
        </div>
        <h2>Termo de Autorização</h2>
        <div class="grid">
            <div class="maca">
                <form action="gerar_pdf.php" method="POST">
                    <p>Eu, abaixo identificado, declaro que no gozo pleno de minhas faculdades mentais e psíquicas pelo presente e na melhor forma de direito, autorizo o(a) artista a executar sobre meu corpo ou de meu/minha filho(a) menor nascido, abaixo identificado, que em minha companhia reside e pelo qual sou inteiramente responsável a prática da tatuagem...</p>

                    <label>Nome do cliente/Responsável: </label>
                    <input type="text" name="nome_responsavel" required><br>

                    <label>RG: </label>
                    <input type="text" name="rg_responsavel" required><br>

                    <label>CPF: </label>
                    <input type="text" name="cpf_responsavel" required><br>

                    <label>Data de Nascimento: </label>
                    <input type="date" name="nascimento_responsavel" required><br>

                    <label>O cliente é menor de idade?</label>
                    <select id="isMenor" name="isMenor" onchange="toggleMenorFields()" required>
                        <option value="" disabled selected>Selecione...</option>
                        <option value="nao">Não</option>
                        <option value="sim">Sim</option>
                    </select><br><br>

                    <div id="menorFields" style="display: none;">
                        <h3>Informações do Menor</h3>
                        <label>Nome do menor: </label>
                        <input type="text" name="nome_menor"><br>

                        <label>RG: </label>
                        <input type="text" name="rg_menor"><br>

                        <label>CPF: </label>
                        <input type="text" name="cpf_menor"><br>

                        <label>Data de Nascimento: </label>
                        <input type="date" name="nascimento_menor"><br>
                    </div>

                    <label>Assinatura (digite seu nome completo): </label>
                    <input type="text" name="assinatura_responsavel" required><br><br>

                    <input type="submit" value="Assinar e Gerar PDF">
                </form>
            </div>
        </div>
    </div>
    <script>
        function toggleMenorFields() {
            var isMenor = document.getElementById('isMenor').value;
            var menorFields = document.getElementById('menorFields');
            var nomeMenor = document.querySelector('input[name="nome_menor"]');
            var rgMenor = document.querySelector('input[name="rg_menor"]');
            var cpfMenor = document.querySelector('input[name="cpf_menor"]');
            var nascimentoMenor = document.querySelector('input[name="nascimento_menor"]');

            if (isMenor === 'sim') {
                menorFields.style.display = 'block';
                nomeMenor.setAttribute('required', 'required');
                rgMenor.setAttribute('required', 'required');
                cpfMenor.setAttribute('required', 'required');
                nascimentoMenor.setAttribute('required', 'required');
            } else {
                menorFields.style.display = 'none';
                nomeMenor.removeAttribute('required');
                rgMenor.removeAttribute('required');
                cpfMenor.removeAttribute('required');
                nascimentoMenor.removeAttribute('required');
            }
        }
    </script>
</body>
</html>
