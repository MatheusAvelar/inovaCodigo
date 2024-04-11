<?php
session_start();

// Verifica se o usuário está logado, se não, redireciona para a página de login
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: login.php");
    exit;
}

// Recupera os itens do carrinho da sessão, se houver
$cartItems = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu Carrinho</title>
    <link rel="stylesheet" href="css/styles.css">
    <!-- Adicione seus estilos CSS adicionais aqui -->

    <!-- Font Awesome para ícones -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

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
            <div id="cart-icon">
                <i class="fas fa-shopping-cart"></i> <!-- Ícone do carrinho -->
                <span id="cart-counter" class="cart-counter">0</span> <!-- Contador de itens no carrinho -->
            </div>
        </nav>
    </header>

    <h1>Meu Carrinho</h1>

    <div class="cart-container">
        <?php if (!empty($cartItems)) : ?>
            <table>
                <thead>
                    <tr>
                        <th>Produto</th>
                        <th>Quantidade</th>
                        <th>Preço Unitário</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($cartItems as $item) : ?>
                        <tr>
                            <td>Nome do Produto</td> <!-- Substitua pelo nome real do produto -->
                            <td><?php echo $item['quantity']; ?></td>
                            <td>R$ 50,00</td> <!-- Substitua pelo preço real do produto -->
                            <td>R$ <?php echo $item['quantity'] * 50; ?></td> <!-- Calcula o total -->
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <button>Finalizar Compra</button> <!-- Adicione um botão para finalizar a compra -->
        <?php else : ?>
            <p>O seu carrinho está vazio.</p>
        <?php endif; ?>
    </div>

    <footer>
        <!-- Seu rodapé aqui -->
    </footer>

    <!-- Seus scripts JavaScript aqui -->

    <script>
        // Seus scripts JavaScript adicionais aqui
    </script>
</body>

</html>