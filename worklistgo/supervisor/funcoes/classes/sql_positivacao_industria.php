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

$nivel_3 = 0;
$nivel_2 = 0;
$nivel_1 = 0;
$nivel_0 = 0;


$nivel3 = '<i class="fa fa-star" style="color:#FFC107;" aria-hidden="true"><span class="fq">3</span></i>';
$nivel2 = '<i class="fa fa-star" style="color:#a0a0a0;" aria-hidden="true"><span class="fq">2</span></i>';
$nivel1 = '<i class="fa fa-star" style="color:#af592e;" aria-hidden="true"><span class="fq">1</span></i>';
$nivel0 = '<i class="fa fa-star" style="color:#000000;" aria-hidden="true"><span class="fq">0</span></i>';

$result = pg_query($query);
while ($row = pg_fetch_array($result)) {
    
    switch ($row['fq']){
        case 0: $nivel_0++; break; 
        case 1: $nivel_1++; break; 
        case 2: $nivel_2++; break; 
        case 3: $nivel_3++; break; 
    }
    
    
    echo "<tr>";
    echo '<td style="vertical-align:middle;">' . $row['idcliente'] . '&nbsp;</td>';
    echo '<td>&nbsp;'. $row['razaosocial'] . '</td>';
   // echo defineCorCarteira($row['fq']);
  switch ($row['fq']){
        case 0: echo '<td width=15 align=center> ' . $nivel0. '</td>'; break; 
        case 1: echo '<td width=15 align=center> ' . $nivel1. '</td>'; break; 
        case 2: echo '<td width=15 align=center> ' . $nivel2. '</td>'; break; 
        case 3: echo '<td width=15 align=center> ' . $nivel3. '</td>'; break; 
    }
    
    
    echo '<td style="vertical-align:middle;">&nbsp;'.number_format($row['media'], 0, ',', '.') . '&nbsp;</td>';
    echo defineCorCliPositivado(number_format($row['mes1'], 0, ',', '.'));
    echo defineIconePositivado(number_format($row['mes1'], 0, ',', '.'));
    echo "</tr>"; 
}
?>


<script>
    var nivel0 =<?php echo $nivel_0; ?>;
    var nivel1 =<?php echo $nivel_1; ?>;
    var nivel2 =<?php echo $nivel_2; ?>;
    var nivel3 =<?php echo $nivel_3; ?>;
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
