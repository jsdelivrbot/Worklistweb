<?php
session_start();
include("sistema/config.php");
include("sistema/funcoes.php");
$sql = select("SELECT * FROM user");
if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
    redir(URLBASE . "/users/");
}
$lembrar = (isset($_COOKIE['lembrar']) && $_COOKIE['lembrar'] != '' ? $_COOKIE['lembrar'] : false);
$arr = explode('/', $lembrar);
$login = base64_decode($arr[0]);
$senha = base64_decode($arr[1]);
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
        <link rel="stylesheet" type="text/css" href="css/style.css" />
        <link rel="stylesheet" type="text/css" href="css/topo.css" />
        <link rel="stylesheet" type="text/css" href="css/conteudo.css" />
    </head>
    <body>
        <div id="topo">
            <div id="logo_index">
                Logo
            </div>
            <div id="entrada">
                <form name="entrar" method="post" action="" >
                    <input class="inputs" type="text" name="login" placeholder="E-Mail..."/>
                    <input class="inputs" type="password" name="senha" placeholder="Senha..." />
                    <input class="bottom" type="submit" name="logar" value="Entrar" /><br>
                    <input type="checkbox" name="lembrar" /><label>Mantenha-me conectado!</label>
                    <?php
                    if ($_POST['logar']) {
                        $input['email'] = limpaTexto($_POST['login']);
                        $input['senha'] = limpaTexto($_POST['senha']);
                        $input['lembrar'] = $_POST['lembrar'];
                        if (empty($input['email'])) { //verifica de está vazio o campo nome
                            echo "Informe Seu E-Mail"; //Escreve mensagem na tela
                        } elseif (empty($input['senha'])) { //verifica se o campo senha está vazio
                            echo "Informe sua senha"; //Escreve mensagem na tela
                        } else { //Se o campo email e senha não estiver vazio ele executara este código
                            $getEmail = select("SELECT * FROM user WHERE email='" . $input['email'] . "'"); //seleciona o email digitedo
                            $conta = mysql_num_rows($getEmail); //COnta o numero de linhas do banco de dados
                            if ($conta <= 0) { //Verifica se o email está ou não cadastrado
                                echo "Esse E-Mail não foi cadastrado..."; //Escreve a mensagem na tela caso o email não exista
                            } else { //Executa o bloco de código seguinte se existir o email selecionado
                                $getSenha = select("SELECT * FROM user WHERE senha='" . $input['senha'] . "'"); //Seleciona a senh no banco
                                $conta = mysql_num_rows($getSenha); //Conta o numero de colunas no banco de dados
                                if ($conta <= 0) { //Verifica de não existe a senha
                                    echo "Senha incorreta..."; //Se não existir a senha mostra a mensagem
                                } else { //email e senha tudo Ok, executa o próximo bloco de cmandos
                                    $_SESSION['usuario']['login'] = base64_encode($input['Email']);
                                    $_SESSION['usuario']['senha'] = base64_encode($input['Senha']);
                                    if ($input['Lembrar'] == true) {
                                        setcookie('lembrar', base64_encode($input['Email']) . "/" . base64_encode($input['Senha']), time() + 60 * 60 * 24 * 30, '/');
                                    } else {
                                        setcookie('lembrar', '', time() - 60 * 60 * 24 * 30, '/');
                                    }
                                    if (isset($_SESSION['usuario']) && !empty($_SESSION['usuario'])) {
                                        redir(URLBASE . "/users/");
                                    } else {
                                        redir(URLBASE);
                                    }
                                }
                            }
                        }
                    }
                    ?>
                </form>
            </div>
        </div>
        <div id="conteudo">
            <div id="lateral">
                <div id="esquerda">
                    Esquerda
                    <div id="img">
                        Imagens
                    </div>
                </div>
                <div id="direita">
                    <div id="form">
                        <div id="label">
                            <label>Cadastre-se</label>
                        </div>
                        <form method="post" action="" >
                            <input class="nome_cadas" type="text" name="nome" placeholder="Nome" />
                            <input class="sobre_cadas" type="text" name="sobre" placeholder="Sobrenome" /><br>
                            <input class="inputs_cadas" type="text" name="email" placeholder="E-Mail" /><br>
                            <input class="inputs_cadas" type="text" name="confEmail" placeholder="Confirme seu E-Mail" /><br>
                            <input class="inputs_cadas" type="password" name="senha" placeholder="Senha" /><br>
                            <input class="inputs_cadas" type="password" name="confSenha" placeholder="Confirme a Senha" /><br>
                            <input class="data_cadas" type="date" name="nasc" /><br>
                            <input class="radio_cadas" type="radio" name="sexo" value="masculino" /><label>Masculino</label>
                            <input class="radio_cadas" type="radio" name="sexo" value="feminino" /><label>Feminino</label><br>
                            <input class="botao_cadas" type="submit" name="enviar" value="cadastrar" />
                        </form>
                        <?php
                        if ($_POST['enviar']) {
                            if (!isset($_POST['nome']) || $_POST['nome'] == '') {
                                echo "Preencha o campo Nome";
                            } elseif (!isset($_POST['sobre']) || $_POST['sobre'] == '') {
                                echo "Preencha o campo Sobrenome";
                            } elseif (!isset($_POST['email']) || $_POST['email'] == '') {
                                echo "Preencha o campo E-Mail";
                            } elseif (!preg_match("/^[a-z0-9_\.\-]+@[a-z0-9_\.\-]*[a-z0-9_\-]+\.[a-z]{2,4}$/i", $_POST['email'])) {
                                echo "O E-Mail não é válido";
                            } elseif (!isset($_POST['confEmail']) || $_POST['confEmail'] == '') {
                                echo "Preencha o campo E-Mail";
                            } elseif (!isset($_POST['senha']) || $_POST['senha'] == '') {
                                echo "Preencha o campo senha ";
                            } elseif (!isset($_POST['confSenha']) || $_POST['confSenha'] == '') {
                                echo "Preencha o campo confirmar senha";
                            } elseif ($_POST['nasc'] == '') {
                                echo "Informe sua data de nascimento";
                            } elseif (!isset($_POST['sexo']) || $_POST['sexo'] == '') {
                                echo "Selecione seu sexo";
                            } elseif ($_POST['email'] != $_POST['confEmail']) {
                                echo "Os E-Mails não são iguais";
                            } elseif ($_POST['senha'] != $_POST['confSenha']) {
                                echo "As Senhas não são iguais";
                            } else {
                                $nome = limpaTexto($_POST['nome']);
                                $sobre = limpaTexto($_POST['sobre']);
                                $email = $_POST['email'];
                                $senha = $_POST['senha'];
                                $data = $_POST['nasc'];
                                $sexo = $_POST['sexo'];
                                $tipo = "user";
                                $compEmail = select("SELECT * FROM user WHERE email='$email'");
                                $conta = mysql_num_rows($compEmail);
                                if ($conta > 1) {
                                    echo 'Esse email já foi cadastrado, tente outro!';
                                } else {
                                    $dados = array(
                                        'nome' => $nome,
                                        'sobre' => $sobre,
                                        'email' => $email,
                                        'senha' => $senha,
                                        'data' => $nasc,
                                        'sexo' => $sexo,
                                        'tipo' => $tipo
                                    );
                                    $insert = insert("user", $dados);
                                    echo "Você foi cadastrado com sucesso";
                                    mkdir("/user/users/$nome");
                                    redir(URLBASE . "/users/");
                                    @mysql_free_result($banco);
                                    @mysql_close($conn);
                                }
                            }
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>