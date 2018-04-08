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
$query .="
GROUP BY idvendedor
UNION ALL
--------------- PERÍODO-----------------
SELECT
idvendedor,
0 AS MES1,0 AS MES2,0 AS MES3,0 AS MES4,0 AS MES5, COUNT(DISTINCT idproduto) AS PERIODO
FROM
sys_vendas
WHERE idvendedor = " . $_SESSION['idrepresentante'];
if ($industriaselecionada <> 1) {
    $query .= " and idindustria IN($industriaselecionada) ";
}
$query .= " GROUP BY idvendedor
)A
WHERE
A.idvendedor = ".$_SESSION['idrepresentante'].
" GROUP BY A.idvendedor" ; 

$result = pg_query($query);
$row = pg_fetch_array($result);

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
echo "<tr>";
echo '<td align="center" style="font-weight:bold;"> ' . $mix. '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $mes5 . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $mes4 . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $mes3 . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $mes2 . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $mes1 . '</td>';
echo '<td align="center" style="font-weight:bold;"> ' . $periodo . '</td>';
//defineCorInadimplencia($row['inad']);
echo "</tr>";

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
    var arrayMes=['MIX',<?php atualizaMeses2(4)?>'PER.'];
    var mix = <?php echo $mix;?>;
    var mes1 =<?php echo $mes1; ?>;
    var mes2 =<?php echo $mes2; ?>;
    var mes3 =<?php echo $mes3; ?>;
    var mes4 =<?php echo $mes4; ?>;
    var mes5 =<?php echo $mes5; ?>;
    var periodo=<?php echo $periodo; ?>;
</script>


            <!-- Bootstrap core JavaScript-->
            <script src="../vendor/jquery/jquery.min.js"></script>
            <script src="../vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
            <!-- Core plugin JavaScript-->
            <script src="../vendor/jquery-easing/jquery.easing.min.js"></script>
            <!-- Page level plugin JavaScript-->
            <script src="../vendor/chart.js/Chart.min.js"></script>
            <script src="../vendor/datatables/jquery.dataTables.js"></script>
            <script src="../vendor/datatables/dataTables.bootstrap4.js"></script>
            <!-- Custom scripts for all pages-->
            <script src="../js/sb-admin.min.js"></script>
            <!-- Custom scripts for this page-->
            <script src="../js/sbs-admin-datatables.min.js"></script>
            <script src="js/graficomix.js"></script>
            