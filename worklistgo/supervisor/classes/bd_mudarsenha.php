<?php

// chamar criador de sessão de login
session_start();
if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

// Requerendo Classe de conexão com o banco
require_once 'bd.php';

// Instanciando a classe em uma variavel bd
$bd = new bd();

//carregando campos do formulario para as variaveis
 $novasenha = $_POST['novasenha'];
 $repetirsenha = $_POST['repetirsenha'];

if ($novasenha == ''){
  header('Location: ../mudar-senha.php?erro=2');   
}else
if($novasenha == $repetirsenha){
//montando a query para execução no banco
 $query = "update sys_acessos set senha = '$novasenha' where idusuario= ".$_SESSION['idusuario'];

if($result = pg_query($query)){
    header('Location: ../index.php'); 
}
}else{
   header('Location: ../mudar-senha.php?erro=1');  
}


