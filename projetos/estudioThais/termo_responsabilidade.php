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

                    <label>Nome do cliente/Responsável: </label>
                    <input type="text" name="nome_responsavel" required><br>

                    <label>RG: </label>
                    <input type="text" name="rg_responsavel" id="rg" maxlength="12" required><br>

                    <label>CPF: </label>
                    <input type="text" name="cpf_responsavel" id="cpf" maxlength="14" required><br>

                    <label>Data de Nascimento: </label>
                    <input type="date" name="nascimento_responsavel" required><br>

                    <label>Endereço: </label>
                    <input type="text" name="endereco_responsavel" required><br>

                    <label>Bairro: </label>
                    <input type="text" name="bairro_responsavel" required><br>

                    <label>Cidade: </label>
                    <input type="text" name="cidade_responsavel" required><br>

                    <label>Estado: </label>
                    <select name="estado_responsavel" required>
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
                    <input type="text" name="telefone_responsavel" required><br>

                    <label>Profissão: </label>
                    <input type="text" name="profissao_responsavel" required><br>

                    <label>Email: </label>
                    <input type="email" name="email_responsavel" required><br>

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
                        <input type="text" name="rg_menor" id="rg_menor" maxlength="12"><br>

                        <label>CPF: </label>
                        <input type="text" name="cpf_menor" id="cpf_menor" maxlength="14"><br>

                        <label>Data de Nascimento: </label>
                        <input type="date" name="nascimento_menor"><br>

                        <label>Endereço: </label>
                        <input type="text" name="endereco_menor"><br>

                        <label>Bairro: </label>
                        <input type="text" name="bairro_menor"><br>

                        <label>Cidade: </label>
                        <input type="text" name="cidade_menor"><br>

                        <label>Estado: </label>
                        <select name="estado_menor">
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
                        <input type="text" name="telefone_menor"><br>

                        <label>Email: </label>
                        <input type="email" name="email_menor"><br>
                    </div>

                    <label>Local e desenho da tatuagem: </label>
                    <input type="text" name="local_tatuagem"><br>

                    <label>Data: </label>
                    <input type="date" name="data_tatuagem"><br>

                    <label>Nome do tatuador: </label>
                    <input type="text" name="nome_tatuador"><br><br>

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
                        <input type="radio" name="alergia" value="sim" required> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="alergia" value="nao" required> 
                        <span class="checkmark"></span> Não
                    </label><br>

                    <label>É hemofílico? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="hemofilico" value="sim" required> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="hemofilico" value="nao" required> 
                        <span class="checkmark"></span> Não
                    </label><br>

                    <!--<label>Grávida ou amamentando? </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="gravida" value="sim" required> 
                        <span class="checkmark"></span> Sim
                    </label>
                    <label class="custom-checkbox">
                        <input type="radio" name="gravida" value="nao" required> 
                        <span class="checkmark"></span> Não
                    </label><br>-->

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
