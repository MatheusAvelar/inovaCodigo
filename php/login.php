<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SVG</title>
    <link rel="stylesheet" href="Style.css">
    <link rel="icon" href="img\vacina.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

    <script src="MetodosVisuais.js"> </script>
    <script src="MetodosConfirmacao.js"> </script>

</head>

<body>

    <div class="content">

        <div class="logo">
            <img src="img\vacina.png" id="logo">
        </div>
        <div class="textoPrincipal">
            <h1>Bem vindo ao SGV
                <h2>Sistema de Gerenciamento de Vacinas</h2>

            </h1>
        </div>



        <div class="formularios">
            <div id="CriarConta">
                <h3>Crie sua conta:</h3>
                <div class="mb-3">
                    <label for="e-mail" class="form-label">E-mail</label>
                    <input type="email" class="form-control" id="emailCriar1" placeholder="Insira seu e-mail">
                </div>

                <div class="mb-3">
                    <label for="e-mail" class="form-label">Nome</label>
                    <input type="text" class="form-control" id="nomeCriar" placeholder="Insira aqui seu nome">
                </div>

                <div class="mb-3">
                    <label for="senha2" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="senhaCriar1" placeholder="Insira sua senha">
                </div>

                <label for="senha2" class="form-label">Gênero</label>

                <div class="form-check">
                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault1">
                    <label class="form-check-label" for="flexRadioDefault1">
                        Masculino
                    </label>
                </div>

                <div class="form-check">

                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault2" checked>
                    <label class="form-check-label" for="flexRadioDefault2">
                        Feminino
                    </label>


                </div>
                <div class="form-check">

                    <input class="form-check-input" type="radio" name="flexRadioDefault" id="flexRadioDefault3" checked>
                    <label class="form-check-label" for="flexRadioDefault3">
                        Outro
                    </label>

                </div>

                <form class="needs-validation" novalidate>

                    <div class="form-row">
                        <div class="col-md-6 mb-3">
                            <label for="validationCustom03">Cidade</label>
                            <input type="text" class="form-control" id="validationCustom03" placeholder="Cidade" required>
                            <div class="invalid-feedback">
                                Por favor insira uma cidade válida.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationCustom04">Estado</label>
                            <input type="text" class="form-control" id="validationCustom04" placeholder="Estado"
                                required>
                            <div class="invalid-feedback">
                                Por favor insira um estado válido.
                            </div>
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="validationCustom05">CEP</label>
                            <input type="text" class="form-control" id="validationCustom05" placeholder="CEP" required>
                            <div class="invalid-feedback">
                                Por favor insira um CEP válido.
                            </div>
                        </div>
                    </div>
                </form>

                <button type="button" onclick="confirmarCriacao()" class="btn btn-success">Registrar</button>
            </div>

            <div id="Login">
                <h3>Entre na sua conta:</h3>
                <div class="mb-3">
                    <label for="e-mail" class="form-label">E-mail</label>
                    <input type="text" class="form-control" id="EmailEntrar" placeholder="Insira seu e-mail">
                </div>

                <div class="mb-3">
                    <label for="senha2" class="form-label">Senha</label>
                    <input type="password" class="form-control" id="SenhaEntrar" placeholder="Insira sua senha">
                </div>
                <button type="button" onclick="buscarUsuario()" class="btn btn-success">Entrar na conta</button>
            </div>
            <div class="botoes">
                <button onclick="mostrarLayoutLogin()" type="button" class="btn btn-success">Já possui uma
                    conta</button>
                <p>Não tem uma conta?</p>
                <button onclick="mostrarLayoutCriarConta()" type="button" class="btn btn-success">Criar conta<div
                        class="container-fluid !direction !spacing">
                    </div></button>
            </div>

        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-kenU1KFdBIe4zVF0s0G1M5b4hcpxyD9F7jL+jjXkk+Q2h455rYXK/7HAuoJl+0I4"
        crossorigin="anonymous"></script>
</body>

</html>