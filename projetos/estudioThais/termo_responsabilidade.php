<?php
session_start();

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
            <div class="maca">
                <form action="php/gerar_pdf.php" method="POST">
                    <p>Eu, abaixo identificado, declaro que no gozo pleno de minhas faculdades mentais e psíquicas pelo presente e na melhor forma de direito, autorizo o(a) artista a executar sobre meu corpo ou de meu/minha filho(a) menor nascido, abaixo identificado, que em minha companhia reside e pelo qual sou inteiramente responsável a prática da tatuagem.</p>

                    <p>Assumo na qualidade de genitor(a) do(a) menor, plena responsabilidade pelo trabalho ora autorizado. É de minha livre vontade declarar que isento de responsabilidade civil ou criminal ao tatuador(a), seja de ordem médica, estética ou ainda defeitos da própria inscrição, salvo aquelas decorrentes de imperícia técnica. Ficando ainda plenamente ciente de que o procedimento da tatuagem tem caráter permanente, não podendo ser removida.</p>

                    <p>Declaro ainda, ser do meu conhecimento as técnicas a serem executadas, os materiais a serem utilizados, bem como fui informado e tenho total ciência dos procedimentos e cuidados que devem ser executados por mim ou por meu/minha filho(a) durante o período recomendado pelo tatuador, com a finalidade de evitar qualquer complicação no período de cicatrização do local. Reconheço finalmente que a tatuagem se trata de um processo artesanal e como tal não comporta perfeição.</p>

                    <!-- Dados do Cliente -->

                    <h3>Dados do Cliente</h3>
                    <input type="hidden" name="id" value="<?php echo htmlspecialchars($_GET['id'] ?? '', ENT_QUOTES); ?>" >

                    <label>Nome: </label>
                    <input type="text" name="nome_cliente" value="<?php echo htmlspecialchars($_GET['nome_cliente'] ?? '', ENT_QUOTES); ?>" required><br>

                    <label>RG: </label>
                    <input type="text" name="rg_cliente" id="rg_cliente" maxlength="12" oninput="mascaraRG(this)" required><br>

                    <label>CPF: </label>
                    <input type="text" name="cpf_cliente" id="cpf_cliente" maxlength="14" oninput="mascara(this)" required><br>

                    <label>Data de Nascimento: </label>
                    <input type="date" name="nascimento_cliente" required><br>

                    <label>Endereço: </label>
                    <input type="text" name="endereco_cliente" required><br>

                    <label>Bairro: </label>
                    <input type="text" name="bairro_cliente" required><br>

                    <label>Cidade: </label>
                    <input type="text" name="cidade_cliente" required><br>

                    <label>Estado: </label>
                    <select name="estado_cliente" required>
                        <option value="">Selecione</option>
                        <option value="AC">Acre</option>
                        <option value="AL">Alagoas</option>
                        <option value="AP">Amapá</option>
                        <option value="AM">Amazonas</option>
                        <option value="BA">Bahia</option>
                        <option value="CE">Ceará</option>
                        <option value="DF">Distrito Federal</option>
                        <option value="ES">Espírito Santo</option>
                        <option value="GO">Goiás</option>
                        <option value="MA">Maranhão</option>
                        <option value="MT">Mato Grosso</option>
                        <option value="MS">Mato Grosso do Sul</option>
                        <option value="MG">Minas Gerais</option>
                        <option value="PA">Pará</option>
                        <option value="PB">Paraíba</option>
                        <option value="PR">Paraná</option>
                        <option value="PE">Pernambuco</option>
                        <option value="PI">Piauí</option>
                        <option value="RJ">Rio de Janeiro</option>
                        <option value="RN">Rio Grande do Norte</option>
                        <option value="RS">Rio Grande do Sul</option>
                        <option value="RO">Rondônia</option>
                        <option value="RR">Roraima</option>
                        <option value="SC">Santa Catarina</option>
                        <option value="SP">São Paulo</option>
                        <option value="SE">Sergipe</option>
                        <option value="TO">Tocantins</option>
                    </select><br>

                    <label>Telefone: </label>
                    <input type="text" name="telefone_cliente" id="telefone_cliente" value="<?php echo htmlspecialchars($_GET['telefone_cliente'] ?? '', ENT_QUOTES); ?>" required><br>

                    <label>Email: </label>
                    <input type="email" name="email_cliente" value="<?php echo htmlspecialchars($_GET['email_cliente'] ?? '', ENT_QUOTES); ?>" required><br>

                    <label>O cliente é menor de idade?</label>
                    <select id="isMenor" name="isMenor" onchange="toggleClienteFields()" required>
                        <option value="" disabled selected>Selecione...</option>
                        <option value="nao">Não</option>
                        <option value="sim">Sim</option>
                    </select><br>
                    
                    <div class="grid" id="responsavel">
                        <div class="maca">
                            <h3>Dados do Responsável</h3>
                            <label>Nome: </label><br>
                            <input type="text" name="nome_responsavel" id="nome_responsavel"><br>

                            <label>RG: </label><br>
                            <input type="text" name="rg_responsavel" id="rg_responsavel" maxlength="12" oninput="mascaraRG(this)"><br>

                            <label>CPF: </label><br>
                            <input type="text" name="cpf_responsavel" id="cpf_responsavel" maxlength="14" oninput="mascara(this)"><br>

                            <label>Data de Nascimento: </label><br>
                            <input type="date" name="nascimento_responsavel" id="nascimento_responsavel"><br>

                            <label>Endereço: </label><br>
                            <input type="text" name="endereco_responsavel" id="endereco_responsavel"><br>

                            <label>Bairro: </label><br>
                            <input type="text" name="bairro_responsavel" id="bairro_responsavel"><br>

                            <label>Cidade: </label><br>
                            <input type="text" name="cidade_responsavel" id="cidade_responsavel"><br>

                            <label>Estado: </label><br>
                            <select name="estado_responsavel" id="estado_responsavel">
                                <option value="">Selecione</option>
                                <option value="AC">Acre</option>
                                <option value="AL">Alagoas</option>
                                <option value="AP">Amapá</option>
                                <option value="AM">Amazonas</option>
                                <option value="BA">Bahia</option>
                                <option value="CE">Ceará</option>
                                <option value="DF">Distrito Federal</option>
                                <option value="ES">Espírito Santo</option>
                                <option value="GO">Goiás</option>
                                <option value="MA">Maranhão</option>
                                <option value="MT">Mato Grosso</option>
                                <option value="MS">Mato Grosso do Sul</option>
                                <option value="MG">Minas Gerais</option>
                                <option value="PA">Pará</option>
                                <option value="PB">Paraíba</option>
                                <option value="PR">Paraná</option>
                                <option value="PE">Pernambuco</option>
                                <option value="PI">Piauí</option>
                                <option value="RJ">Rio de Janeiro</option>
                                <option value="RN">Rio Grande do Norte</option>
                                <option value="RS">Rio Grande do Sul</option>
                                <option value="RO">Rondônia</option>
                                <option value="RR">Roraima</option>
                                <option value="SC">Santa Catarina</option>
                                <option value="SP">São Paulo</option>
                                <option value="SE">Sergipe</option>
                                <option value="TO">Tocantins</option>
                            </select><br>

                            <label>Telefone: </label><br>
                            <input type="text" name="telefone_responsavel" id="telefone_responsavel" required><br>

                            <label>Profissão: </label><br>
                            <input type="text" name="profissao_responsavel" id="profissao_responsavel" required><br>

                            <label>Email: </label><br>
                            <input type="email" name="email_responsavel" id="email_responsavel" required><br>
                        </div>
                    </div>
                    <!-- Dados da Tatuagem -->

                    <label>Local e desenho da tatuagem: </label>
                    <input type="text" name="local_tatuagem"><br>

                    <label>Data: </label>
                    <input type="date" name="data_tatuagem"><br>

                    <label>Nome do tatuador: </label>
                    <input type="text" name="nome_tatuador"><br><br>

                    <!-- Perguntas de Saude -->

                    <label>Faz uso de algum medicamento? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="medicamento" value="sim" onchange="toggleMedicamentoField()" required> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="medicamento" value="nao" onchange="toggleMedicamentoField()" required> 
                        <span class="checkmark"></span> Não
                    </label><br>
                    <input type="text" name="medicamento_nome" placeholder="Qual medicamento" id="medicamento_nome" style="display: none;"><br>

                    <label>Já contraiu hepatite? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="hepatite" value="sim" onchange="toggleHepatiteField()" required> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="hepatite" value="nao" onchange="toggleHepatiteField()" required> 
                        <span class="checkmark"></span> Não
                    </label><br>
                    <input type="text" name="hepatite_tipo" placeholder="Qual tipo e quando" id="hepatite_tipo" style="display: none;"><br>

                    <label>Tem problemas de cicatrização? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="cicatrizacao" value="sim" required> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="cicatrizacao" value="nao" required> 
                        <span class="checkmark"></span> Não
                    </label><br>

                    <label>Tem problemas de desmaio? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="desmaio" value="sim" required> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="desmaio" value="nao" required> 
                        <span class="checkmark"></span> Não
                    </label><br>

                    <label>Portador de HIV?</label>
                    <label class="custom-checkbox">
                        <input type="radio" name="hiv" value="sim" required> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="hiv" value="nao" required> 
                        <span class="checkmark"></span> Não
                    </label><br>

                    <label>Tem doença autoimune? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="autoimune" value="sim" required> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="autoimune" value="nao" required> 
                        <span class="checkmark"></span> Não
                    </label><br>

                    <label>É epilético? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="epileptico" value="sim" required> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="epileptico" value="nao" required> 
                        <span class="checkmark"></span> Não
                    </label><br>

                    <label>É alérgico á algo? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="alergia" value="sim" onchange="toggleAlergiaField()" required> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="alergia" value="nao" onchange="toggleAlergiaField()" required> 
                        <span class="checkmark"></span> Não
                    </label><br>
                    <input type="text" name="alergia_nome" placeholder="Alergia de que?" id="alergia_nome" style="display: none;"><br>

                    <label>É hemofílico? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="hemofilico" value="sim" required> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="hemofilico" value="nao" required> 
                        <span class="checkmark"></span> Não
                    </label><br>

                    <!-- Assinatura -->
                     
                    <label>Assinatura (digite seu nome completo): </label>
                    <input type="text" name="assinatura_responsavel" required><br><br>

                    <button type="submit">Assinar e Gerar PDF</button>
                </form>
            </div>
        </div>
    </div>
    
    <script>
        function toggleMedicamentoField() {
            var medicamentoSim = document.querySelector('input[name="medicamento"][value="sim"]');
            var medicamentoNome = document.getElementById('medicamento_nome');
            medicamentoNome.style.display = medicamentoSim.checked ? 'block' : 'none';
        }

        function toggleAlergiaField() {
            var alergiaSim = document.querySelector('input[name="alergia"][value="sim"]');
            var alergiaNome = document.getElementById('alergia_nome');
            alergiaNome.style.display = alergiaSim.checked ? 'block' : 'none';
        }

        function toggleHepatiteField() {
            var hepatiteSim = document.querySelector('input[name="hepatite"][value="sim"]');
            var hepatiteTipo = document.getElementById('hepatite_tipo');
            hepatiteTipo.style.display = hepatiteSim.checked ? 'block' : 'none';
        }

        function toggleClienteFields() {
            var isMenor = document.getElementById('isMenor').value;
            var responsavel = document.getElementById('responsavel');
            var nomeResponsavel = document.getElementById('nome_responsavel'); // Campo que será obrigatório
            var rgResponsavel = document.getElementById('rg_responsavel');
            var cpfResponsavel = document.getElementById('cpf_responsavel');
            var nascimentoResponsavel = document.getElementById('nascimento_responsavel');
            var enderecoResponsavel = document.getElementById('endereco_responsavel');
            var bairroResponsavel = document.getElementById('bairro_responsavel');
            var telefoneResponsavel = document.getElementById('telefone_responsavel');
            var profissaoResponsavel = document.getElementById('profissao_responsavel');
            var emailResponsavel = document.getElementById('email_responsavel');
            

            if (isMenor === 'sim') {
                responsavel.style.display = 'block';
                nomeResponsavel.setAttribute('required', 'required');
                rgResponsavel.setAttribute('required', 'required');
                cpfResponsavel.setAttribute('required', 'required');
                nascimentoResponsavel.setAttribute('required', 'required');
                enderecoResponsavel.setAttribute('required', 'required');
                bairroResponsavel.setAttribute('required', 'required');
                telefoneResponsavel.setAttribute('required', 'required');
                profissaoResponsavel.setAttribute('required', 'required');
                emailResponsavel.setAttribute('required', 'required');
            } else {
                responsavel.style.display = 'none';
                nomeResponsavel.removeAttribute('required');
                rgResponsavel.removeAttribute('required');
                cpfResponsavel.removeAttribute('required');
                nascimentoResponsavel.removeAttribute('required');
                enderecoResponsavel.removeAttribute('required');
                bairroResponsavel.removeAttribute('required');
                telefoneResponsavel.removeAttribute('required');
                profissaoResponsavel.removeAttribute('required');
                emailResponsavel.removeAttribute('required');
            }
        }


        function mascaraRG(input) {
            let value = input.value.replace(/\D/g, ""); // Remove tudo que não é dígito
            value = value.replace(/(\d{2})(\d)/, "$1.$2"); // Coloca o ponto após os primeiros 2 dígitos
            value = value.replace(/(\d{3})(\d)/, "$1.$2"); // Coloca o ponto após os próximos 3 dígitos
            value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2"); // Coloca o traço antes dos últimos 2 dígitos
            input.value = value;
        }

        function mascara(i){
            var v = i.value;
            
            if(isNaN(v[v.length-1])){ // impede entrar outro caractere que não seja número
                i.value = v.substring(0, v.length-1);
                return;
            }
            
            i.setAttribute("maxlength", "14");
            if (v.length == 3 || v.length == 7) i.value += ".";
            if (v.length == 11) i.value += "-";
        
        }
        
        // Inicializar o estado dos campos dependentes
        toggleMedicamentoField();
        toggleHepatiteField();
        toggleClienteFields();
        toggleAlergiaField();
    </script>
</body>
</html>
