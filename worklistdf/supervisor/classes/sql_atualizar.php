<?php
// Worklist DF Distribuidora - Pagina ajustada para a nova estrutura de supervisão.
// chamar criador de sessão de login
if (!isset($_SESSION)) {

    session_start();
}

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';

$rca = $_POST['rca'];

$bd = new bd();

//Montando Query
$queryrca = "select 
usuario,
idrepresentante
from 
sys_acessos 
where 
tipoacesso in ( 'representante','televendas' ) ";
if ($rca == 1) {
    $queryrca .= " and idrepresentante in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $queryrca .= " and idrepresentante = '$rca' ";
}

$resultrca = pg_query($queryrca);
while ($row = pg_fetch_array($resultrca)) {

//Montando Query
$query = "insert into sys_atualizador (idvendedor,solicitacao,status) values (" . $row['idrepresentante'] . ",CURRENT_TIMESTAMP,'Em Andamento')";

//executando a query montada acima
$result = pg_query($query);
}

$query = " select 
 idsolicitador as id, 
 B.nome as representante,
 TO_CHAR(solicitacao,'DD/MM/YYYY HH24:MI:SS') as solicitacao,  
 status 
 from 
 sys_atualizador a,
 sys_acessos b
 where
 a.idvendedor = b.idrepresentante ";
$query .= " and status ='Em Andamento' ";

if ($rca == 1) {
    $query .= " and b.idrepresentante in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $query .= " and b.idrepresentante = $rca ";
}

$result = pg_query($query);




while ($row = pg_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['representante'] . "</td>";
    echo "<td>" . $row['solicitacao']    . "</td>";
    echo "<td>" . $row['status']      . "</td>";
    echo "</tr>";
}
