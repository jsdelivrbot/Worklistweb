<?php

// chamar criador de sessão de login
if (!isset($_SESSION)) {

    session_start();
}

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';

$bd = new bd();

//Montando Query
$query = "insert into sys_atualizador (idvendedor,solicitacao,status) values (" . $_SESSION['idrepresentante'] . ",CURRENT_TIMESTAMP,'Em Andamento')";

//executando a query montada acima
$result = pg_query($query);

$query = " select ";
$query .= " idsolicitador as id, ";
$query .= " TO_CHAR(solicitacao,'DD/MM/YYYY HH24:MI:SS') as solicitacao,";
$query .= " TO_CHAR(resposta,'DD/MM/YYYY HH24:MI:SS') as resposta,";    
$query .= " TO_CHAR(AGE(CURRENT_TIMESTAMP,solicitacao),'HH24:MI:SS') as tempo, "; 
$query .= " status ";
$query .= " from ";
$query .= " sys_atualizador ";
$query .= " where ";
$query .= " status ='Em Andamento' ";
$query .= " and idvendedor = ".$_SESSION['idrepresentante'];

$result = pg_query($query);




while ($row = pg_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['id']          . "</td>";
    echo "<td>" . $row['solicitacao'] . "</td>";
    echo "<td>" . $row['resposta']    . "</td>";
    echo "<td>" . $row['tempo']       . "</td>";
    echo "<td>" . $row['status']      . "</td>";
    echo "</tr>";
}
