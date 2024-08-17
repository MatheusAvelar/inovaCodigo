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
                <form action="php/gerar_pdf.php" method="POST">
                    <p>Eu, abaixo identificado, declaro que no gozo pleno de minhas faculdades mentais e psíquicas pelo presente e na melhor forma de direito, autorizo o(a) artista a executar sobre meu corpo ou de meu/minha filho(a) menor nascido, abaixo identificado, que em minha companhia reside e pelo qual sou inteiramente responsável a prática da tatuagem.</p>

                    <p>Assumo na qualidade de genitor(a) do(a) menor, plena responsabilidade pelo trabalho ora autorizado. É de minha livre vontade declarar que isento de responsabilidade civil ou criminal ao tatuador(a), seja de ordem médica, estética ou ainda defeitos da própria inscrição, salvo aquelas decorrentes de imperícia técnica. Ficando ainda plenamente ciente de que o procedimento da tatuagem tem caráter permanente, não podendo ser removida.</p>

                    <p>Declaro ainda, ser do meu conhecimento as técnicas a serem executadas, os materiais a serem utilizados, bem como fui informado e tenho total ciência dos procedimentos e cuidados que devem ser executados por mim ou por meu/minha filho(a) durante o período recomendado pelo tatuador, com a finalidade de evitar qualquer complicação no período de cicatrização do local. Reconheço finalmente que a tatuagem se trata de um processo artesanal e como tal não comporta perfeição.</p>

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

                    <label>Local e desenho da tatuagem: </label>
                    <input type="text" name="local_tatuagem"><br>

                    <label>Data: </label>
                    <input type="date" name="data_tatuagem"><br>

                    <label>Nome do tatuador: </label>
                    <input type="text" name="nome_tatuador"><br><br>

                    <label>Tem problemas de cicatrização? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="cicatrizacao" value="sim"> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="cicatrizacao" value="nao"> 
                        <span class="checkmark"></span> Não
                    </label><br>

                    <label>Tem problemas de desmaio? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="desmaio" value="sim"> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="desmaio" value="nao"> 
                        <span class="checkmark"></span> Não
                    </label><br>

                    <label>É hemofílico? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="hemofilico" value="sim"> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="hemofilico" value="nao"> 
                        <span class="checkmark"></span> Não
                    </label><br>

                    <label>Já contraiu hepatite? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="hepatite" value="sim"> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="hepatite" value="nao"> 
                        <span class="checkmark"></span> Não
                    </label>
                    <input type="text" name="hepatite_tipo" placeholder="Tipo e quando" style="display: none;"><br>

                    <label>Portador de HIV? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="hiv" value="sim"> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="hiv" value="nao"> 
                        <span class="checkmark"></span> Não
                    </label><br>

                    <label>Tem doença autoimune? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="autoimune" value="sim"> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="autoimune" value="nao"> 
                        <span class="checkmark"></span> Não
                    </label><br>

                    <label>É epilético? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="epileptico" value="sim"> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="epileptico" value="nao"> 
                        <span class="checkmark"></span> Não
                    </label><br>

                    <label>Faz uso de algum medicamento? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="medicamento" value="sim"> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="medicamento" value="nao"> 
                        <span class="checkmark"></span> Não
                    </label>
                    <input type="text" name="medicamento_nome" placeholder="Qual medicamento" style="display: none;"><br>

                    <label>É alérgico a algo? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="alergia" value="sim"> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="alergia" value="nao"> 
                        <span class="checkmark"></span> Não
                    </label>
                    <input type="text" name="alergia_nome" placeholder="Qual alergia" style="display: none;"><br><br>

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

        // Mostrar ou esconder campos adicionais de saúde conforme a seleção
        document.querySelectorAll('input[name="hepatite"]').forEach(el => {
            el.addEventListener('change', function () {
                document.querySelector('input[name="hepatite_tipo"]').style.display = this.value === 'sim' ? 'inline' : 'none';
            });
        });

        document.querySelectorAll('input[name="medicamento"]').forEach(el => {
            el.addEventListener('change', function () {
                document.querySelector('input[name="medicamento_nome"]').style.display = this.value === 'sim' ? 'inline' : 'none';
            });
        });

        document.querySelectorAll('input[name="alergia"]').forEach(el => {
            el.addEventListener('change', function () {
                document.querySelector('input[name="alergia_nome"]').style.display = this.value === 'sim' ? 'inline' : 'none';
            });
        });
    </script>
</body>
</html>
