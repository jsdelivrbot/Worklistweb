<?php
// chamar criador de sessão de login
if(!isset($_SESSION))

{

session_start();

}


//require_once 'funcoes/funcoes.php';
require_once 'bd.php';
require_once '../funcoes/funcoes.php';
$bd = new bd();

$query = "select distinct grupo from sys_produtos where idindustria = 9365 order by grupo desc";
$result = pg_query($query);
echo "<table border=1;>";

echo "<tr>";
echo '<td align="center" style="font-weight:bold;">CLIENTE</td>';
while ($row = pg_fetch_array($result)) {

echo '<td align="center" style="font-weight:bold;">' . $row['grupo'] . '</td>';

}
echo "<tr>"; 
echo "</table>";
?>

