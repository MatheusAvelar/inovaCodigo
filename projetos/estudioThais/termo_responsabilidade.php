<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Termo de Autorização</title>
    <link rel="stylesheet" href="css/style.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.7/jquery.inputmask.min.js"></script>
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
            <div class="maca"><?
                include "php/form_termo_responsabilidade.php";
                ?>
            </div>
        </div>
    </div>
    
    <script>
        function toggleMedicamentoField() {
            var medicamentoSim = document.querySelector('input[name="medicamento"][value="sim"]');
            var medicamentoNome = document.getElementById('medicamento_nome');
            medicamentoNome.style.display = medicamentoSim.checked ? 'block' : 'none';
        }

        function toggleHepatiteField() {
            var hepatiteSim = document.querySelector('input[name="hepatite"][value="sim"]');
            var hepatiteTipo = document.getElementById('hepatite_tipo');
            hepatiteTipo.style.display = hepatiteSim.checked ? 'block' : 'none';
        }

        function toggleMenorFields() {
            var isMenor = document.getElementById('isMenor').value;
            var menorFields = document.getElementById('menorFields');
            if (isMenor === 'sim') {
                menorFields.style.display = 'block';
                
                // Aplicar as máscaras nos campos do menor
                document.getElementById('rg_menor').inputmask({ mask: '99.999.999-9' });
                document.getElementById('cpf_menor').inputmask({ mask: '999.999.999-99' });
            } else {
                menorFields.style.display = 'none';
            }
        }

        // Aplicar as máscaras nos campos do responsável
        document.getElementById('rg').inputmask({ mask: '99.999.999-9' });
        document.getElementById('cpf').inputmask({ mask: '999.999.999-99' });

        // Inicializar o estado dos campos dependentes
        toggleMedicamentoField();
        toggleHepatiteField();
        toggleMenorFields();
    </script>
</body>
</html>
