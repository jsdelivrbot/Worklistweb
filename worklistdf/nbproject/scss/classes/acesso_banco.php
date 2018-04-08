<?php

// chamar criador de sessão de login
session_start();

// Requerendo Classe de conexão com o banco
require_once '../classes/bd.php';

// Instanciando a classe em uma variavel bd
$bd = new bd();



//carregando campos do formulario para as variaveis
$usuario = $_POST['usuario'];
$senha = $_POST['senha'];
$conectado = $_POST['conectado'];

// criação do cookie de conexão para manter conectado.
if($conectado == 1){
    setcookie('usuarioworklist', $usuario, (time() + (30 * 24 * 3600)));
    setcookie('senhaworklist', $senha, (time() + (30 * 24 * 3600)));
    setcookie('checked', 'checked', (time() + (30 * 24 * 3600)));
}  else {
    //deletar cooki de manter conectao
    setcookie('usuarioworklist');
    setcookie('senhaworklist');
    setcookie('checked');
}

//montando a query para execução no banco
$query = "select nome,sobrenome,usuario,senha,idrepresentante from sys_acessos where usuario = '$usuario'";

//executando a query montada acima
$result = pg_query($query);

//verificando se houve resultado no processamento da query
if (pg_num_rows($result) == 0) {
    echo "0 records";
} else {
    $row = pg_fetch_array($result);
    $usuario2 = $row['usuario'];
    $senha2 = $row['senha'];
    $idusuario = $row['idrepresentante'];
}

$input['lembrar'] = true;

//comparando dos dados da query com os dados do formulario e retornando true ou false
if ($usuario == $usuario2 && $senha == $senha2) {
    $_SESSION['logado'] = "SIM";
    $_SESSION['idrepresentante'] = $row['idrepresentante'];
    $_SESSION['usuario'] = $row['nome'] . "&nbsp" . $row['sobrenome'];
   // carrega session com dados de usuario caso o cookie exista no dispositivo 
    $_SESSION['usuarioworklist'] = $_COOKIE['usuarioworklist'];
    $_SESSION['senhaworklist'] = $_COOKIE['senhaworklist'];
    $_SESSION['checked'] = $_COOKIE['checked'];
    
//montando a query para execução no banco
$query = "insert into sys_controleacesso (idusuario,dataacesso,telaacessada) values (".$idusuario.",current_timestamp,'login')";

//executando a query montada acima
$result = pg_query($query);

//abri index após execução do controle de acesso.
header('Location: ../index.php');

} else {
    echo "Acesso Bloqueado";
    session_destroy();
}





