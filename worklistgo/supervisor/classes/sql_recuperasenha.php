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

//Montando Query
$queryrca = "Select nome,linha as equipe,usuario,senha from sys_acessos ";
if ($rca == 1) {
    $queryrca .=" where idrepresentante in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $queryrca .=" where idrepresentante = $rca ";
}


$result = pg_query($queryrca);




while ($row = pg_fetch_array($result)) {
    echo "<tr>";
    echo "<td>" . $row['nome'] . "</td>";
    echo "<td>" . $row['equipe'] . "</td>";
    echo "<td>" . $row['usuario'] . "</td>";
    echo "<td>" . $row['senha'] . "</td>";
    echo "</tr>";
}
