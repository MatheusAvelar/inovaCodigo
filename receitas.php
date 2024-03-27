<?php
session_start();

// Verifica se o usuário está logado, se não, redireciona para a página de login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="icon" href="img/ico.ico" type="image/x-icon">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" type="text/css"
        href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <title>Inova Código - Política de Privacidade</title>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1516963750502427"
     crossorigin="anonymous"></script>
</head>

<body>
    
    <header class="navbar">
        <nav class="nav-links">
            <a href="index.html">Home</a>
            <a href="projetos.html">Projetos</a>
            <a href="publicacoes.html">Publicações</a>
            <a href="gerador.html">Geradores</a>
            <a href="projetos/controleFinanceiro/login.php">Controle Financeiro</a>
            <a href="kanban.html">Kanban</a>
            <a href="jogos.html">Jogos</a>
            <a href="https://matheusavelar.github.io/">Currículo</a>
            <a href="apropriacao.php">Apropriação de Horas</a>
            <a href="logout.php">Sair</a>
        </nav>
    </header>

    <div class="consulta-cep">
        <h1>Insira os ingredientes que você tem em casa:</h1>
        <textarea id="ingredientes"></textarea>
        <br>
        <button onclick="sugerirReceitas()">Sugerir Receitas</button>
        <div id="receitas"></div>
    </div>

    <footer class="footer">
        <div class="footer-content">
            <div class="footer-logo">
                <img src="img/nome.png" alt="Logo da Marca no Rodapé">
            </div>
            <div class="footer-social">
                <a href="https://www.linkedin.com/in/matheusavelar/" target="blank"><img align="center" src="img/linkedin.png"
                    alt="https://www.linkedin.com/in/matheusavelar/" /></a>
            <a href="https://fb.com/matheus.dapaz.33" target="blank"><img align="center" src="img/facebook.png"
                    alt="matheus.dapaz.33" /></a>
            <a href="https://instagram.com/matheusnutela" target="blank"><img align="center" src="img/instagram.png"
                    alt="matheusnutela" /></a>
            <a href="https://github.com/MatheusAvelar" target="blank"><img align="center" src="img/github.png"
                    alt="matheusnutela" /></a>
            <a href="https://api.whatsapp.com/send?phone=5531993018766" target="blank"><img align="center"
                    src="img/whatsapp.png" alt="matheusnutela" /></a>
            </div>
        </div>
        <div class="footer-links">
            <a href="termosUso.html">Termos de Uso</a>
            <a href="politica.html">Política de Privacidade</a>
            <a href="contato.html">Contato</a>
        </div>
        <div class="footer-bottom">
            &copy; 2023 Inova Código. Todos os direitos reservados.
        </div>
    </footer>
    <script>
        async function traduzirTexto(texto, idiomaOrigem, idiomaDestino) {
            const response = await fetch(`https://translate.googleapis.com/translate_a/single?client=gtx&sl=${idiomaOrigem}&tl=${idiomaDestino}&dt=t&q=${encodeURI(texto)}`);
            const data = await response.json();
            return data[0][0][0];
        }

        async function buscarReceitas(ingredientesParametro, apiKey) {
            const response = await fetch(`https://api.spoonacular.com/recipes/findByIngredients?ingredients=${ingredientesParametro}&number=5&apiKey=${apiKey}`);
            const receitas = await response.json();
        
            // Traduzir os títulos das receitas para português
            for (let receita of receitas) {
                receita.title = await traduzirTexto(receita.title, 'en', 'pt');
            }
        
            return receitas;
        }

        async function sugerirReceitas() {
            const ingredientesUsuario = document.getElementById('ingredientes').value.split('\n').map(ingrediente => ingrediente.trim()).filter(ingrediente => ingrediente !== '');

            if (ingredientesUsuario.length === 0) {
                alert('Por favor, insira pelo menos um ingrediente.');
                return;
            }

            // Traduzir ingredientes para inglês
            const ingredientesTraduzidos = await Promise.all(ingredientesUsuario.map(ingrediente => traduzirTexto(ingrediente, 'pt', 'en')));
            const ingredientesParametro = ingredientesTraduzidos.join(',');

            const apiKey = 'ae9e5c674e8146f6b4f0be6fc08e4f60'; // Sua chave de API do Spoonacular

            try {
                const receitas = await buscarReceitas(ingredientesParametro, apiKey);
                exibirReceitas(receitas);
            } catch (error) {
                console.error('Ocorreu um erro ao buscar as receitas:', error);
                alert('Ocorreu um erro ao buscar as receitas. Por favor, tente novamente mais tarde.');
            }
        }

        function exibirReceitas(receitas) {
            const receitasDiv = document.getElementById('receitas');
            receitasDiv.innerHTML = '<h2>Receitas sugeridas:</h2>';
            if (receitas.length === 0) {
                receitasDiv.innerHTML += '<p>Nenhuma receita encontrada com os ingredientes fornecidos.</p>';
            } else {
                const listaReceitas = document.createElement('ul');
                receitas.forEach(receita => {
                    const itemReceita = document.createElement('li');
                    itemReceita.textContent = receita.title;
                    listaReceitas.appendChild(itemReceita);
                });
                receitasDiv.appendChild(listaReceitas);
            }
        }
    </script>
</body>

</html>