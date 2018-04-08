<?php
// Worklist Goiás Saúde - Pagina ajustada para a nova estrutura de supervisão.
// chamar criador de sessão de login
if (!isset($_SESSION)) {

    session_start();
}

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

$rca = $_POST['rca'];

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';

$bd = new bd();

$query = " select ";
$query .= " b.nome as representante, ";
$query .= " TO_CHAR(solicitacao,'DD/MM/YYYY HH24:MI:SS') as solicitacao,";
$query .= " TO_CHAR(resposta,'DD/MM/YYYY HH24:MI:SS') as resposta,";    
$query .= " TO_CHAR(AGE(resposta,solicitacao),'HH24:MI:SS') as tempo, "; 
$query .= " status ";
$query .= " from ";
$query .= " sys_atualizador a, sys_acessos b";
$query .= " where ";
$query .= " a.idvendedor = b.idrepresentante ";
if ($rca == 1) {
    $query .= " and b.idrepresentante in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca)  ";
} else {
    $query .= " and b.idrepresentante = '$rca' ";
}
$query .= " and solicitacao > CURRENT_DATE-2  ";
$query .= " order by resposta desc ";

$result = pg_query($query);




while ($row = pg_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['representante'] . "</td>";
    echo "<td>" . $row['resposta']    . "</td>";
    echo "<td>" . $row['tempo']       . "</td>";
    echo "<td>" . $row['status']      . "</td>";
    echo "</tr>";
}
