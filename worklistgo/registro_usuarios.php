<?php
$erro_nome = isset($_GET['erro_nome']) ? $_GET['erro_nome'] : 0;
$erro_sobrenome = isset($_GET['erro_sobrenome']) ? $_GET['erro_sobrenome'] : 0;
$erro_usuario = isset($_GET['erro_usuario']) ? $_GET['erro_usuario'] : 0;
$erro_codigo = isset($_GET['erro_codigo']) ? $_GET['erro_codigo'] : 0;
$erro_email = isset($_GET['erro_email']) ? $_GET['erro_email'] : 0;
$erro_senha1 = isset($_GET['erro_senha1']) ? $_GET['erro_senha1'] : 0;
$erro_senha2 = isset($_GET['erro_senha2']) ? $_GET['erro_senha2'] : 0;
$erro_senha3 = isset($_GET['erro_senha3']) ? $_GET['erro_senha3'] : 0;
$cadastrado = isset($_GET['cadastrado']) ? $_GET['cadastrado'] : 0;
$erro_sql = isset($_GET['erro_sql']) ? $_GET['erro_sql'] : 0;
?>


<!DOCTYPE html>
<html lang="pt">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="O Worklist é um sistema de monitoramento de indicadores de vendas." content="">
        <meta name="Worknet" content="Worknet">
        <link rel="icon" href="img/favicon.png"> <!--Coloca Icone da aba do navegador-->
        <title>Worklist - Cadastro de Usuários</title>
        <!-- Bootstrap core CSS-->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom fonts for this template-->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Custom styles for this template-->
        <link href="css/sb-admin.css" rel="stylesheet">
        <link href="css/registro_usuario.css" rel="stylesheet">
    </head>

    <body class="bg-dark">
        <div class="container">
            <div class="card card-register mx-auto mt-5">
                <div class="card-header">Registrar Usuário</div>
                <div class="card-body">
                    <form action="classes/bd_registro_usuarios.php" method="post">
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="nome" id="teste">Primeiro Nome</label>
                                    <input class="form-control" id="nome" name="nome" type="text" aria-describedby="nomeHelp" placeholder="Digite Nome">
                                </div>
                                <div class="col-md-12">
                                    <label for="sobrenome">Segundo Nome</label>
                                    <input class="form-control" id="sobrenome" name="sobrenome" type="text" aria-describedby="sobrenomeHelp" placeholder="Digite Sobrenome">
                                </div>
                                <div class="col-md-12">
                                    <label for="usuario">Usuário</label>
                                    <input class="form-control" id="usuario"  name="usuario" type="text" aria-describedby="usuarioHelp" placeholder="Digite Usuário">
                                </div>
                                <div class="col-md-12">
                                    <label for="codigo">Codigo de Representante</label>
                                    <input class="form-control" id="codigo" name="codigo" tyid="codigo"pe="text" aria-describedby="codigoHelp" placeholder="Digite Código">
                                </div>
                                <div class="col-md-12">
                                    <label for="equipe">Assistente/Supervisor</label>
                                    <select class="form-control" name="equipe" id="equipe">
                                        <option value="farma1">Farma 01</option>
                                        <option value="farma2">Farma 02</option>
                                        <option value="correlatos1">Correlatos 01</option>
                                        <option value="correlatos2">Correlatos 02</option>
                                        <option value="kleyhertz">Kley Hertz</option>
                                        <option value="prospecção">prospecção</option>
                                        <option value="geolab">Geolab</option>
                                        <option value="vago">Não Tem</option>
                                    </select>
                                </div>
                                <div class="col-md-12" name="tipoacesso" >
                                    <label for="tipoacesso">Tipo de Acesso</label>
                                    <select class="form-control" id="tipoacesso" name="tipoacesso">
                                        <option value="representante">Representante</option>
                                        <option value="supervisor">Supervisor</option>
                                        <option value="supervisor">Assistente</option>
                                    </select>
                                </div>
                                <div class="col-md-12">
                                    <label for="email">Endereço de Email</label>
                                    <input class="form-control" id="email" name="email" type="email" aria-describedby="emailHelp" placeholder="Digite seu Email">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="form-row">
                                <div class="col-md-12">
                                    <label for="senha">Senha</label>
                                    <input class="form-control" id="senha" name="senha" type="password" placeholder="Senha">
                                </div>
                                <div class="col-md-12">
                                    <label for="confirmacao_senha">Confirmação de Senha</label>
                                    <input class="form-control" id="confirmacao_senha"  name="confirmacao_senha" type="password" placeholder="Confirme Senha">
                                </div>
                            </div>
                        </div>
                        <?php
                        if ($erro_nome) {
                            echo "<div class = 'alert alert-danger' role = 'alert'><strong>Erro: </strong>Digite nome.</div>";
                        }
                        if ($erro_sobrenome) {
                            echo "<div class = 'alert alert-danger' role = 'alert'><strong>Erro: </strong>Digite sobrenome.</div>";
                        }
                        if ($erro_usuario) {
                            echo "<div class = 'alert alert-danger' role = 'alert'><strong>Erro: </strong>Digite usuário.</div>";
                        }
                        if ($erro_codigo == 1) {
                            echo "<div class = 'alert alert-danger' role = 'alert'><strong>Erro: </strong>Digite codigo.</div>";
                        }
                        if ($erro_codigo == 2) {
                            echo "<div class = 'alert alert-danger' role = 'alert'><strong>Erro: </strong>Usuário já Cadastrado.</div>";
                        }
                        if ($erro_email) {
                            echo "<div class = 'alert alert-danger' role = 'alert'><strong>Erro: </strong>Digite email.</div>";
                        }
                        if ($erro_senha1) {
                            echo "<div class = 'alert alert-danger' role = 'alert'><strong>Erro: </strong>Digite senha.</div>";
                        }
                        if ($erro_senha2) {
                            echo "<div class = 'alert alert-danger' role = 'alert'><strong>Erro: </strong>Confirme sua senha.</div>";
                        }
                        if ($erro_senha3) {
                            echo "<div class = 'alert alert-danger' role = 'alert'><strong>Erro: </strong>Senhas não são identicas.</div>";
                        }
                        if ($cadastrado) {
                            echo "<div class = 'alert alert-success' role = 'alert'><strong>Mensagem: </strong>Cadastro Efetuado com Sucesso</div>";
                        }
                        if ($erro_sql) {
                            echo "<div class = 'alert alert-danger' role = 'alert'><strong>Erro: </strong>Não foi possivel cadastrar usuário.</div>";
                        }
                        ?>
                        <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Registrar</button>
                    </form>

                    <div class="text-center">
                        <a class="d-block small mt-3" href="login.php">Logar no Sistema</a>
                        <a class="d-block small" href="forgot-password.html">Esqueceu sua senha?</a>
                    </div>
                </div>
            </div>
        </div>
        <!-- Bootstrap core JavaScript-->
        <script src="vendor/jquery/jquery.min.js"></script>
        <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
        <!-- Core plugin JavaScript-->
        <script src="vendor/jquery-easing/jquery.easing.min.js"></script>
    </body>

</html>
