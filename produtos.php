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
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-..." crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <title>Minha Loja Online</title>
    <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1516963750502427" crossorigin="anonymous"></script>
    <style>
        /* Estilos adicionais para a página de produtos */
        .product-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
        }

        .product-card {
            width: 300px;
            margin: 20px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            text-align: center;
        }

        .product-card img {
            max-width: 100%;
            height: auto;
        }

        .product-card h2 {
            margin-top: 10px;
        }

        .product-card p {
            margin-top: 5px;
        }

        .add-to-cart-btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
        }
        /* CSS para a animação do contador */
        .counter-animation {
            animation: pulse 0.5s ease-in-out;
        }

        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.2);
            }
            100% {
                transform: scale(1);
            }
        }
    </style>
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
            <a href="receitas.html">Receitas</a>
            <a href="logout.php">Sair</a>
            <a href="carrinho.php" class="cart-icon">
                <i class="fas fa-shopping-cart"></i> <!-- Ícone do carrinho -->
                <span id="cart-counter" class="cart-counter">0</span> <!-- Contador de itens no carrinho -->
            </a>
        </nav>
    </header>

    <h1>Nossos Produtos</h1>

    <div class="product-container">
        <!-- Exemplo de produto -->
        <div class="product-card">
            <img src="https://static.vecteezy.com/system/resources/previews/028/047/017/non_2x/3d-check-product-free-png.png" alt="Produto 1">
            <h2>Produto 1</h2>
            <p>R$ 50,00</p>
            <button class="add-to-cart-btn" onclick="addToCart(1)">Adicionar ao Carrinho</button>
        </div>

        <!-- Exemplo de outro produto -->
        <div class="product-card">
            <img src="https://static.vecteezy.com/system/resources/previews/028/047/017/non_2x/3d-check-product-free-png.png" alt="Produto 2">
            <h2>Produto 2</h2>
            <p>R$ 60,00</p>
            <button class="add-to-cart-btn" onclick="addToCart(2)">Adicionar ao Carrinho</button>
        </div>
        <!-- Adicione mais produtos conforme necessário -->
    </div>

    <iframe src="https://www.bet365.com/" width="100%" height="600px" frameborder="0"></iframe>

    <footer>
        <!-- Footer existente -->
    </footer>

    <!-- Scripts -->
    <script>
        function addToCart(productId) {
            var cart = JSON.parse(sessionStorage.getItem("cart")) || [];
            var existingProductIndex = cart.findIndex(item => item.productId === productId);

            if (existingProductIndex !== -1) {
                cart[existingProductIndex].quantity++;
            } else {
                cart.push({
                    productId: productId,
                    quantity: 1
                });
            }

            sessionStorage.setItem("cart", JSON.stringify(cart));

            // Atualiza o contador do carrinho
            updateCartCounter();

            // Anima o contador
            animateCartCounter();
        }

        function updateCartCounter() {
            var cart = JSON.parse(sessionStorage.getItem("cart")) || [];
            var cartCounter = document.getElementById("cart-counter");

            if (cartCounter) {
                var totalItems = cart.reduce((total, item) => total + item.quantity, 0);
                cartCounter.textContent = totalItems;
            }
        }

        function animateCartCounter() {
            var cartCounter = document.getElementById("cart-counter");
            if (cartCounter) {
                cartCounter.classList.add("counter-animation");

                // Remove a classe de animação após um curto período de tempo
                setTimeout(function() {
                    cartCounter.classList.remove("counter-animation");
                }, 500); // Tempo em milissegundos
            }
        }
    </script>
</body>

</html>