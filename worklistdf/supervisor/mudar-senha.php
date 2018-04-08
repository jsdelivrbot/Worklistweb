<?php
// chamar criador de sessão de login

if (!isset($_SESSION)) {
    session_start();
}


if ($_SESSION['logado'] != "SIM") {
    header('Location:login.php');
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
        <meta name="WorklistWEB" content="WorklistWEB">
        <title>Mudar Senha</title>
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
                    <img class="banner_pg_inicial" src="img/dflogo.svg" alt="" >
                </div>

                <div class="card-body">
                    <?php
                    if ($ERRO == 1) {
                        echo "<div class = 'alert alert-danger' role = 'alert'><strong>Mensagem: </strong>As senhas não são iguais.</div>";
                    }
                    if ($ERRO == 2) {
                        echo "<div class = 'alert alert-danger' role = 'alert'><strong>Mensagem: </strong>Digite uma senha.</div>";
                    }
                    ?>
                    <form action="classes/bd_mudarsenha.php" method="post">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Nova Senha</label>
                            <input class="form-control" id="exampleInputEmail1" type="password" name="novasenha"  placeholder="Digite uma senha">
                        </div>
                        <div class="form-group">
                            <label for="exampleInputPassword1">Repetir Nova Senha</label>

                            <input class="form-control" id="exampleInputPassword1" type="password" name="repetirsenha"  placeholder="Repetir senha">
                        </div>

                        <button class="btn btn-md btn-block btn-signin btn-logar" type="submit">Gravar</button>
                    </form>

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
