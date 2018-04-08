<?php
if (!isset($_SESSION)) {
    session_start();
}


$ERRO = isset($_GET['erro']) ? $_GET['erro'] : 0;
?>

<!DOCTYPE html>
<html lang="pt">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="O Worklist é um sistema de monitoramento de indicadores de vendas." content="">
        <meta name="Worknet - Daniel Novais" content="Worknet">
        <title>WorklistWEB - Goiás Saúde</title>
        <!-- Bootstrap core CSS-->
        <link href="vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <!-- Custom fonts for this template-->
        <link href="vendor/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
        <!-- Custom styles for this template-->
        <link href="css/sb-admin.css" rel="stylesheet">
    </head>

    <body class="bg-dark">

        <div class="container">
            <div class="card card-login mx-auto mt-5">
                <div class="card-header" id="banner-login">
                    <img class="banner_pg_inicial" src="img/logogoias.svg" alt="" >
                </div>

<?php
$usuario = isset($_SESSION['usuarioworklist']) ? $_SESSION['usuarioworklist'] : '';
$senha = isset($_SESSION['senhaworklist']) ? $_SESSION['senhaworklist'] : '';
$checked = isset($_SESSION['checked']) ? $_SESSION['checked'] : '';
?>

                <div class="card-body">
<?php
if ($ERRO == 1) {
    echo "<div class = 'alert alert-danger' role = 'alert'><strong>Mensagem: </strong>Usuário ou senha inválidos.</div>";
}
?>
                    <form action="classes/acesso_banco.php" method="post">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Usuário</label>
                            <input class="form-control" id="exampleInputEmail1" type="text" name="usuario" value="<?php echo $usuario ?>" placeholder="Digite seu usuário">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Senha</label>

                            <input class="form-control" id="exampleInputPassword1" type="password" name="senha" value="<?php echo $senha ?>" placeholder="Digite sua senha">
                        </div>

                        <div class="form-group">
                            <div class="form-check">
                                <label class="form-check-label">
                                    <input class="form-check-input" type="checkbox"  name="conectado" value="1" <?php echo $checked ?>> Manter conectado</label>
                            </div>
                        </div>
                        <button class="btn btn-md btn-block btn-signin btn-logar" type="submit">Logar</button>
                    </form>

                    <div class="text-center">
                        <a class="d-block small mt-3 texto-cadastro" href="registro_usuarios.php">Registrar Usuário</a>
                        <a class="d-block small texto-cadastro" href="forgot-password.html">Esqueci minha Senha?</a>
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
