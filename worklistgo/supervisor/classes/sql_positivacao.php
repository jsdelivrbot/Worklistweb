<?php
// Worklist Goiás Saúde - Pagina ajustada para a nova estrutura de supevisão.
// chamar criador de sessão de login
if(!isset($_SESSION))

{

session_start();

}
if(isset($_POST['industriaselecionada'])){
    $industriaselecionada = $_POST['industriaselecionada'];
}else{
    $industriaselecionada=1;
}

$rca = $_POST['rca'];



if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';
require_once '../funcoes/funcoes.php';
$bd = new bd();

$query = "
select 
count(distinct idcliente) as carteira
from 
sys_carteira a,
sys_acessos b
where
b.idrepresentante = a.idvendedor";
if($rca==1){
    $query.=" and b.idrepresentante in(select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca)"; 
    //$query.=" and b.linha in('" . $_SESSION['equipe']."') "; 
}else{
    $query.=" and b.idrepresentante = $rca"; 
}

$result = pg_query($query);
$carteira = pg_fetch_array($result);


$query = "select
a.idcliente,
SUM(a.mes5) as MES5,
SUM(a.mes4) as MES4,
SUM(a.mes3) as MES3,
SUM(a.mes2) as MES2,
SUM(a.mes1) as MES1
from
sys_vendas a,
sys_acessos b
where
b.idrepresentante = a.idvendedor";
if($rca==1){
    $query.=" and b.idrepresentante in( select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) "; 
}else{
    $query.=" and b.idrepresentante = $rca"; 
}

if ($industriaselecionada <> 1) {
  $query .= " and a.idindustria IN(0,$industriaselecionada) ";
}    
$query .=" and a.canalvendas in('VENDEDOR','OLL','VAGO') group by a.idcliente";

echo $query;
$mes1 = 0;
$mes2 = 0;
$mes3 = 0;
$mes4 = 0;
$mes5 = 0;
$positivacao = 0;

$result = pg_query($query);
while ($row = pg_fetch_array($result)) {
    if ($row['mes5'] > 0) {
        $mes5++;
    }
    if ($row['mes4'] > 0) {
        $mes4++;
    }
    if ($row['mes3'] > 0) {
        $mes3++;
    }
    if ($row['mes2'] > 0) {
        $mes2++;
    }
    if ($row['mes1'] > 0) {
        $mes1++;
    }
    if (($row['mes5'] + $row['mes4'] + $row['mes3'] + $row['mes2']) > 0) {
        $positivacao++;
    }
    
    
}
echo "<tr>";
echo '<td align="center" style="font-weight:bold;">' . $carteira['carteira'] . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $mes5 . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $mes4 . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $mes3 . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $mes2 . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $mes1 . '</td>';
echo '<td align="center" style="font-weight:bold;">' . $positivacao . '</td>';
//defineCorInadimplencia($row['inad']);
echo "</tr>";

$cob_mes5 = ($mes5 / $carteira['carteira']) * 100;
$cob_mes4 = ($mes4 / $carteira['carteira']) * 100;
$cob_mes3 = ($mes3 / $carteira['carteira']) * 100;
$cob_mes2 = ($mes2 / $carteira['carteira']) * 100;
$cob_mes1 = ($mes1 / $carteira['carteira']) * 100;
$cob_positivacao = ($positivacao / $carteira['carteira']) * 100;


echo "<tr>";
echo '<td align="center" style="color:black; font-size: 11px;">(100%)</td>';
echo '<td align="center" style="color:black; font-size: 11px;"> ' . number_format($cob_mes5, 1, ',', '.'). '%</td>';
echo '<td align="center" style="color:black; font-size: 11px;"> ' . number_format($cob_mes4, 1, ',', '.') . '%</td>';
echo '<td align="center" style="color:black; font-size: 11px;"> ' . number_format($cob_mes3, 1, ',', '.') . '%</td>';
echo '<td align="center" style="color:black; font-size: 11px;"> ' . number_format($cob_mes2, 1, ',', '.') . '%</td>';
echo '<td align="center" style="color:black; font-size: 11px;"> ' . number_format($cob_mes1, 1, ',', '.') . '%</td>';
echo '<td align="center" style="color:black; font-size: 11px;">' . number_format($cob_positivacao, 1, ',', '.') . '%</td>';
//defineCorInadimplencia($row['inad']);
echo "</tr>";

//montando a query para execução no banco
$query = "insert into sys_controleacesso (idusuario,dataacesso,telaacessada) values (".$_SESSION['idrepresentante'].",current_timestamp,'monitoramento')";

//executando a query montada acima
$result = pg_query($query);

echo $_SESSION['MES1'];

?>

<script>
    var Meses = ['CART.','<?php echo $_SESSION['MES5'] ?>','<?php echo $_SESSION['MES4'] ?>','<?php echo $_SESSION['MES3'] ?>','<?php echo $_SESSION['MES2'] ?>','<?php echo $_SESSION['MES1'] ?>','PER.'];
    var cart =<?php echo $carteira['carteira'];?>;
    var mes1 =<?php echo $mes1;?>;
    var mes2 =<?php echo $mes2;?>;
    var mes3 =<?php echo $mes3;?>;
    var mes4 =<?php echo $mes4;?>;
    var mes5 =<?php echo $mes5;?>;
    var positivacao =<?php echo $positivacao;?>;
</script>


            <script src="../vendor/chart.js/Chart.min.js"></script>

            <script src="js/graficoCarteira.js"></script>
            