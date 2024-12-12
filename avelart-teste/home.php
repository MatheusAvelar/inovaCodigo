<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contato</title>
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
            <a href="https://avelart.inovacodigo.com.br/agendamento.php">
                <img src="img/tatto.jpeg" alt="Logo do Estúdio" class="logo">
            </a>
        </div>
        <div class="welcome-message">
            Bem Vindo!
        </div>
    </header>

    <div class="container">
        <nav id="menu"> 
            <ul>
                <li><a href="">Home</a></li>
                <li><a href="contato.php">Contato</a></li>
                <li><a href="agendamento.php">Minha Conta</a></li>
                <li><a href="php/logout.php">Sair</a></li>
            </ul> 
        </nav>
        <br>
        <h2>Nosso Local</h2>
        <div class="grid">
            <div class="maca">
                <b>Confira no mapa onde estamos localizados:</b>
                <br><br>                
                <div id="map-container">
                    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3750.7239964067485!2d-43.933740014505254!3d-19.936032390660714!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xa6994eaa17c755%3A0x68406fd37f99c948!2sR.%20Santa%20Rita%20Dur%C3%A3o%2C%20339%20-%20Funcion%C3%A1rios%2C%20Belo%20Horizonte%20-%20MG%2C%2030140-111!5e0!3m2!1spt-BR!2sbr!4v1734013337987!5m2!1spt-BR!2sbr" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
                <br><br>
                <div id="address">
                    Endereço: Rua Santa Rita Durão, 339 - Bairro Funcionários, Belo Horizonte, MG
                </div>
            </div>
        </div>
    </div>
</body>
</html>
