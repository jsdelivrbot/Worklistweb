<?php
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

if(isset($_POST['cliente'])){
    $cliente = $_POST['cliente'];
}else{
    $cliente=1;
}


if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';
require_once '../funcoes/funcoes.php';
$bd = new bd();

$query = "SELECT
COUNT(IDPRODUTO) AS MIX
FROM
SYS_PRODUTOS A  
WHERE
estoque > 0 ";
if ($industriaselecionada <> 1) {
  $query .= " and idindustria IN($industriaselecionada) ";
} 

$result = pg_query($query);
$row = pg_fetch_array($result);

$mix = $row['mix'];


$query = "SELECT
A.idvendedor,
SUM(MES5) AS MES5,
SUM(MES4) AS MES4,
SUM(MES3) AS MES3,
SUM(MES2) AS MES2,
SUM(MES1) AS MES1,
SUM(PERIODO) AS PERIODO
FROM
(
--------------- MES 1-----------------
SELECT
idvendedor,
COUNT(DISTINCT idproduto) AS MES1,0 AS MES2,0 AS MES3,0 AS MES4,0 AS MES5, 0 as PERIODO
FROM
sys_vendas
WHERE
MES1 >0";
if ($industriaselecionada <> 1) {
  $query .= " and idindustria IN(0,$industriaselecionada) ";
} 
if ($cliente <> 1) {
  $query .= " and idcliente IN($cliente) ";
} 
$query .="
GROUP BY idvendedor
UNION ALL
--------------- MES 2-----------------
SELECT
idvendedor,
0 AS MES1,COUNT(DISTINCT idproduto) AS MES2,0 AS MES3,0 AS MES4,0 AS MES5, 0 as PERIODO
FROM
sys_vendas
WHERE
MES2 >0";
if ($industriaselecionada <> 1) {
  $query .= " and idindustria IN(0,$industriaselecionada) ";
} 
if ($cliente <> 1) {
  $query .= " and idcliente IN($cliente) ";
} 
$query .="
GROUP BY idvendedor
UNION ALL
--------------- MES 3-----------------
SELECT
idvendedor,
0 AS MES1,0 AS MES2,COUNT(DISTINCT idproduto) AS MES3,0 AS MES4,0 AS MES5, 0 as PERIODO
FROM
sys_vendas
WHERE
MES3 >0";
if ($industriaselecionada <> 1) {
  $query .= " and idindustria IN(0,$industriaselecionada) ";
} 
if ($cliente <> 1) {
  $query .= " and idcliente IN($cliente) ";
} 
$query .="
GROUP BY idvendedor
UNION ALL
--------------- MES 4-----------------
SELECT
idvendedor,
0 AS MES1,0 AS MES2,0 AS MES3,COUNT(DISTINCT idproduto) AS MES4,0 AS MES5, 0 as PERIODO
FROM
sys_vendas
WHERE
MES4 >0";
if ($industriaselecionada <> 1) {
  $query .= " and idindustria IN(0,$industriaselecionada) ";
} 
if ($cliente <> 1) {
  $query .= " and idcliente IN($cliente) ";
} 
$query .="
GROUP BY idvendedor
UNION ALL
--------------- MES 5-----------------
SELECT
idvendedor,
0 AS MES1,0 AS MES2,0 AS MES3,0 AS MES4,COUNT(DISTINCT idproduto) AS MES5, 0 as PERIODO
FROM
sys_vendas
WHERE
MES5 >0";
if ($industriaselecionada <> 1) {
  $query .= " and idindustria IN(0,$industriaselecionada) ";
} 
if ($cliente <> 1) {
  $query .= " and idcliente IN($cliente) ";
} 
$query .="
GROUP BY idvendedor
UNION ALL
--------------- PERÍODO-----------------
SELECT
idvendedor,
0 AS MES1,0 AS MES2,0 AS MES3,0 AS MES4,0 AS MES5, COUNT(DISTINCT idproduto) AS PERIODO
FROM
sys_vendas
WHERE (MES1+MES2+MES3+MES4+MES5) > 0 and idvendedor = " . $_SESSION['idrepresentante'];
if ($industriaselecionada <> 1) {
    $query .= " and idindustria IN($industriaselecionada) ";
}
if ($cliente <> 1) {
  $query .= " and idcliente IN($cliente) ";
} 
$query .= " GROUP BY idvendedor
)A
WHERE
A.idvendedor = ".$_SESSION['idrepresentante'].
" GROUP BY A.idvendedor" ; 

$result = pg_query($query);
$row = pg_fetch_array($result);

$mes1 = 0;
$mes2 = 0;
$mes3 = 0;
$mes4 = 0;
$mes5 = 0;
$periodo = 0;

$mes1 = $row['mes1'];
$mes2 = $row['mes2'];
$mes3 = $row['mes3'];
$mes4 = $row['mes4'];
$mes5 = $row['mes5'];
$periodo=$row['periodo'];

$percmes1 = number_format((($row['mes1'] / $mix )*100), 2, ',', '.');
$percmes2 = number_format((($row['mes2'] / $mix )*100), 2, ',', '.');
$percmes3 = number_format((($row['mes3'] / $mix )*100), 2, ',', '.');
$percmes4 = number_format((($row['mes4'] / $mix )*100), 2, ',', '.');
$percmes5 = number_format((($row['mes5'] / $mix )*100), 2, ',', '.');
$percperiodo = number_format((($periodo / $mix )*100), 2, ',', '.');

$result = pg_query($query);
if(pg_affected_rows($result)==0){
  echo "<tr>";
echo '<td align="center" style="font-weight:bold;">'.$mix.'</td>';
echo '<td align="center" style="font-weight:bold;">0</td>';
echo '<td align="center" style="font-weight:bold;">0</td>';
echo '<td align="center" style="font-weight:bold;">0</td>';
echo '<td align="center" style="font-weight:bold;">0</td>';
echo '<td align="center" style="font-weight:bold;">0</td>';
echo '<td align="center" style="font-weight:bold;">0</td>';
echo "</tr>";?>
<script>
    var arrayMes=['MIX',<?php atualizaMeses2(4)?>'PER.'];
    var mix = 0;
    var mes1 =0;
    var mes2 =0;
    var mes3 =0;
    var mes4 =0;
    var mes5 =0;
    var periodo=0;

</script>

<?php
}else{
 echo "<tr>";
echo '<td align="center" style="font-weight:bold;"> ' . $mix. '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $mes5 . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $mes4 . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $mes3 . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $mes2 . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $mes1 . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $periodo . '</td>';
echo "</tr>";  ?>
    <script>
    var mix = <?php echo $mix;?>;
    var mes1 =<?php echo $mes1; ?>;
    var mes2 =<?php echo $mes2; ?>;
    var mes3 =<?php echo $mes3; ?>;
    var mes4 =<?php echo $mes4; ?>;
    var mes5 =<?php echo $mes5; ?>;
    var periodo=<?php echo $periodo; ?>;

</script>
<?php
}


echo "<tr>";
echo '<td align="center" > 100% </td>';
echo '<td align="center" > ' . $percmes5 . '%</td>';
echo '<td align="center" > ' . $percmes4 . '%</td>';
echo '<td align="center" > ' . $percmes3 . '%</td>';
echo '<td align="center" > ' . $percmes2 . '%</td>';
echo '<td align="center" > ' . $percmes1 . '%</td>';
echo '<td align="center" > ' . $percperiodo . '%</td>';
//defineCorInadimplencia($row['inad']);
echo "</tr>";

//montando a query para execução no banco
$query = "insert into sys_controleacesso (idusuario,dataacesso,telaacessada) values (".$_SESSION['idrepresentante'].",current_timestamp,'mix')";

//executando a query montada acima
$result = pg_query($query);


?>

<script>
    var MesesMix = ['MIX','<?php echo $_SESSION['MES5'] ?>','<?php echo $_SESSION['MES4'] ?>','<?php echo $_SESSION['MES3'] ?>','<?php echo $_SESSION['MES2'] ?>','<?php echo $_SESSION['MES1'] ?>','PER.'];
    var mix = 0;
    var mes1 =0;
    var mes2 =0;
    var mes3 =0;
    var mes4 =0;
    var mes5 =0;
    var periodo=0;
    
    var mix = <?php echo $mix; ?>;
    var mes1 =<?php echo $mes1; ?>;
    var mes2 =<?php echo $mes2; ?>;
    var mes3 =<?php echo $mes3; ?>;
    var mes4 =<?php echo $mes4; ?>;
    var mes5 =<?php echo $mes5; ?>;
    var periodo=<?php echo $periodo; ?>;
</script>



            <script src="../vendor/chart.js/Chart.min.js"></script>
            <script src="js/graficomix.js"></script>
            