<?php
// chamar criador de sessão de login
if(!isset($_SESSION))

{

session_start();

}

$industriaselecionada = $_POST['industriaselecionada'];
echo $industriaselecionada;

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');   
}

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';
require_once '../funcoes/funcoes.php';
$bd = new bd();

$query = "
select
a.idrca,
sum(mes5) as mes5,
sum(mes4) as mes4,
sum(mes3) as mes3,
sum(mes2) as mes2,
sum(mes1) as mes1,
(sum(mes5)+sum(mes4)+sum(mes3)+sum(mes2))/4 as media
from
(
----------- MES 1 -----------
select
idrca,
(sum(custo)/ sum(venda))*100 as mes1,0 as mes2,0 as mes3,0 as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-0 MONTH','YYYY/MM')";

if ($industriaselecionada <> 1) {
  $query .= " and idindus = ($industriaselecionada) ";
} 

$query .= " group by idrca
UNION ALL
----------- MES 2 -----------
select
idrca,
0 as mes1,(sum(custo)/ sum(venda))*100 as mes2,0 as mes3,0 as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-1 MONTH','YYYY/MM')";

if ($industriaselecionada <> 1) {
  $query .= " and idindus = ($industriaselecionada) ";
} 

$query .= " group by idrca
UNION ALL
----------- MES 3 -----------
select
idrca,
0 as mes1,0 as mes2,(sum(custo)/ sum(venda))*100 as mes3,0 as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-2 MONTH','YYYY/MM')";

if ($industriaselecionada <> 1) {
  $query .= " and idindus = ($industriaselecionada) ";
} 

$query .= " group by idrca
UNION ALL
----------- MES 4 -----------
select
idrca,
0 as mes1,0 as mes2,0 as mes3,(sum(custo)/ sum(venda))*100 as mes4,0 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-3 MONTH','YYYY/MM')";

if ($industriaselecionada <> 1) {
  $query .= " and idindus = ($industriaselecionada) ";
} 

$query .= " group by idrca
----------- MES 5 -----------
UNION ALL
select
idrca,
0 as mes1,0 as mes2,0 as mes3,0 as mes4,(sum(custo)/ sum(venda))*100 as mes5
from sys_cmv
where MES = to_char(CAST(CURRENT_DATE AS DATE) + INTERVAL '-4 MONTH','YYYY/MM')";

if ($industriaselecionada <> 1) {
  $query .= " and idindus = ($industriaselecionada) ";
} 

$query .= " group by idrca)a
where idrca= ".$_SESSION['idrepresentante'];
$query .= " group by
a.idrca";

$result = pg_query($query);
$row = pg_fetch_array($result);

echo "<tr>";
echo '<td align="center" style="font-weight:bold;"> ' . number_format($row['mes5'], 2, ',', '.'). '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . number_format($row['mes4'], 2, ',', '.') . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . number_format($row['mes3'], 2, ',', '.') . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . number_format($row['mes2'], 2, ',', '.') . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . number_format($row['mes1'], 2, ',', '.') . '</td>';
defineCorCMV(number_format($row['media'], 2, ',', '.')) ;
//defineCorInadimplencia($row['inad']);
echo "</tr>";

//montando a query para execução no banco
$query = "insert into sys_controleacesso (idusuario,dataacesso,telaacessada) values (".$_SESSION['idrepresentante'].",current_timestamp,'CMV')";

//executando a query montada acima
$result = pg_query($query);


?>

<script>
    var MesesCMV = ['<?php echo $_SESSION['MES5'] ?>','<?php echo $_SESSION['MES4'] ?>','<?php echo $_SESSION['MES3'] ?>','<?php echo $_SESSION['MES2'] ?>','<?php echo $_SESSION['MES1'] ?>','Média'];
    var mes1 =<?php echo $row['mes1'];?>;
    var mes2 =<?php echo $row['mes2'];?>;
    var mes3 =<?php echo $row['mes3'];?>;
    var mes4 =<?php echo $row['mes4'];?>;
    var mes5 =<?php echo $row['mes5'];?>;
    var media =<?php echo $row['media'];?>;
</script>



            <script src="../vendor/chart.js/Chart.min.js"></script>
           
            <script src="js/graficoCMV.js"></script>
            