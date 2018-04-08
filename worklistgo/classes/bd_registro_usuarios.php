<?php
// EMPRESA: GOIÁS SAÚDE
// Requerendo Classe de conexão com o banco
require_once '../classes/bd.php';

// Instanciando a classe em uma variavel bd
$bd = new bd();

// chamar criador de sessão de login
session_start();

//carregando campos do formulario para as variaveis
 $nome = $_POST['nome'];
 $sobrenome = $_POST['sobrenome'];
 $usuario = $_POST['usuario'];
 $codigo = $_POST['codigo'];
 $email = $_POST['email'];
 $senha = $_POST['senha'];
 $confirmacao_senha = $_POST['confirmacao_senha'];
 $tipoacesso = $_POST['tipoacesso'];
 $equipe = $_POST['equipe'];
 
 $retorno_get = '';

if (!$nome){
    $retorno_get="erro_nome=1&";
}
if (!$sobrenome){
    $retorno_get.="erro_sobrenome=1&";
}
if (!$usuario){
    $retorno_get.="erro_usuario=1&";
}
if (!$codigo){
    $retorno_get.="erro_codigo=1&";
}
if (!$email){
    $retorno_get.="erro_email=1&";
}
if (!$senha){
    $retorno_get.="erro_senha1=1&";
}
if (!$confirmacao_senha){
    $retorno_get.="erro_senha2=2&";
}
if($senha != $confirmacao_senha){
    $retorno_get.="erro_senha3=3";
}


//select para detectar se usuári já existe
$query = "select idrepresentante from sys_acessos where idrepresentante = $codigo";

// executar a query acima
$result = pg_query($query);
// verifica a existencia de registro no banco de dados.  
if (pg_num_rows($result) > 0) {
    // retorna via Get o o codigo de erro para o form de cadastro de usuário
    $retorno_get .= "erro_codigo=2&";
}


if($retorno_get == ''){
//montando a query para execução no banco
 $query = "insert into sys_acessos (nome,sobrenome,usuario,idrepresentante,email,senha,tipoacesso,linha) values ('$nome','$sobrenome','$usuario',$codigo,'$email','$senha','$tipoacesso','$equipe')";

if($result = pg_query($query)){
    header('Location: ../registro_usuarios.php?cadastrado=1'); 
}else{
    header('Location: ../registro_usuarios.php?erro_sql=1');   
}
}else{
    header('Location: ../registro_usuarios.php?'.$retorno_get);
}

