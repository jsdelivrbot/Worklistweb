<?php
if(!isset($_SESSION)){
session_start();
}

if (isset($_POST['industriaselecionada'])) {
    $industriaselecionada = $_POST['industriaselecionada'];
    $aux = 1;
} else {
    $industriaselecionada = 1;
    $aux=0;
}

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

if ($aux == 1) {
    require_once '../funcoes/funcoes.php';
}else{
    require_once 'funcoes/funcoes.php';
}

require_once 'bd.php';
$bd = new bd();

$query = "
SELECT
  A.idcliente,
  A.razaosocial,
 
 case when sum(a.mes2) >=1 and sum(a.mes3) >=1 and sum(a.mes4) >=1 then 3 
  when sum(a.mes2) >=1 and sum(a.mes3) >=1 and sum(a.mes4) <1 then 2
  when sum(a.mes2) >=1 and sum(a.mes3) <1 and sum(a.mes4) >=1 then 2 
  when sum(a.mes2) <1 and sum(a.mes3) >=1 and sum(a.mes4) >=1 then 2
  when sum(a.mes2) >=1 and sum(a.mes3) <1 and sum(a.mes4) <1 then 1 
  when sum(a.mes2) <1 and sum(a.mes3) >=1 and sum(a.mes4) <1 then 1 
  when sum(a.mes2) <1 and sum(a.mes3) <1 and sum(a.mes4) >=1 then 1
  when sum(a.mes2) <1 and sum(a.mes3) <1 and sum(a.mes4) <1 then 0 end as fq,
   
  ROUND(SUM(MES5),2) AS MES5,
  ROUND(SUM(MES4),2) AS MES4,
  ROUND(SUM(MES3),2) AS MES3,
  ROUND(SUM(MES2),2) AS MES2,
  ROUND(SUM(MES1),2) AS MES1,
   ROUND((SUM(a.MES5) + SUM(a.MES4) + SUM(a.MES3) + SUM(a.MES2))/4,2) as media, 
   ROUND((SUM(a.MES5) + SUM(a.MES4) + SUM(a.MES3) + SUM(a.MES2)),2) AS TOTAL 
  FROM
      sys_vendas A, sys_carteira B
  WHERE
	A.idcliente = B.idcliente AND
	A.idvendedor = B.idvendedor AND
       A.idvendedor IN (" . $_SESSION['idrepresentante'] . ")";
if ($industriaselecionada <> 1) {
    $query .= " and a.idindustria IN(0,$industriaselecionada)";
}
$query .= " and a.canalvendas in('VENDEDOR','OLL','VAGO')";
$query .= "GROUP BY   A.idcliente,  A.razaosocial order by FQ DESC,total desc ";

$nivel3 = 0;
$nivel2 = 0;
$nivel1 = 0;
$nivel0 = 0;

$result = pg_query($query);
while ($row = pg_fetch_array($result)) {
    
    switch ($row['fq']){
        case 0: $nivel0++; break; 
        case 1: $nivel1++; break; 
        case 2: $nivel2++; break; 
        case 3: $nivel3++; break; 
    }
    
    echo 'DANIEL';
    
    echo "<tr>";
    echo '<td style="vertical-align:middle;">' . $row['idcliente'] . '&nbsp;</td>';
    echo '<td>&nbsp;'. $row['razaosocial'] . '</td>';
    echo defineCorCarteira($row['fq']);
    echo '<td style="vertical-align:middle;">&nbsp;'.number_format($row['media'], 0, ',', '.') . '&nbsp;</td>';
    echo defineCorCliPositivado(number_format($row['mes1'], 0, ',', '.'));
    echo defineIconePositivado(number_format($row['mes1'], 0, ',', '.'));
    echo "</tr>"; 
}
?>


<script>
    var nivel0 =<?php echo $nivel0; ?>;
    var nivel1 =<?php echo $nivel1; ?>;
    var nivel2 =<?php echo $nivel2; ?>;
    var nivel3 =<?php echo $nivel3; ?>;
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
<script src="js/graficoNivel.js"></script>
