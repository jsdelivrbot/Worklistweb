<?php
if (!isset($_SESSION)) {
    session_start();
}

if (isset($_POST['idcliente'])) {
    $idcliente = $_POST['idcliente'];
} else {
    $idcliente = 1;
}

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
    
}

$rca = $_POST['rca'];
?>

<!-- Example DataTables Card-->

<?php
require_once 'bd.php';
$bd = new bd();
$query = "select
b.idindustria,
b.fantasia,
 case when sum(a.mes2) >=1 and sum(a.mes3) >=1 and sum(a.mes4) >=1 then 3 
  when sum(a.mes2) >=1 and sum(a.mes3) >=1 and sum(a.mes4) <1 then 2
  when sum(a.mes2) >=1 and sum(a.mes3) <1 and sum(a.mes4) >=1 then 2 
  when sum(a.mes2) <1 and sum(a.mes3) >=1 and sum(a.mes4) >=1 then 2
  when sum(a.mes2) >=1 and sum(a.mes3) <1 and sum(a.mes4) <1 then 1 
  when sum(a.mes2) <1 and sum(a.mes3) >=1 and sum(a.mes4) <1 then 1 
  when sum(a.mes2) <1 and sum(a.mes3) <1 and sum(a.mes4) >=1 then 1
  when sum(a.mes2) <1 and sum(a.mes3) <1 and sum(a.mes4) <1 then 0 end as fq,
ROUND(SUM(a.mes5),2) as MES5,
ROUND(SUM(a.mes4),2) as MES4,
ROUND(SUM(a.mes3),2) as MES3,
ROUND(SUM(a.mes2),2) as MES2,
ROUND(SUM(a.mes1),2) as MES1,
   ROUND((SUM(a.MES5) + SUM(a.MES4) + SUM(a.MES3) + SUM(a.MES2))/4,2) as media, 
   ROUND((SUM(a.MES5) + SUM(a.MES4) + SUM(a.MES3) + SUM(a.MES2)),2) AS TOTAL 
from
sys_vendas a,
sys_industrias b,
sys_acessos c
where
b.idindustria = a.idindustria and
a.idvendedor=c.idrepresentante";

if($rca==1){
    $query.=" and a.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) "; 
}else{
    $query.=" and a.idvendedor = $rca"; 
}

if ($idcliente <> 1) {
    $query .= " and a.idcliente IN($idcliente)  ";
}

$query .= " and a.canalvendas in('VENDEDOR','OLL','VAGO')
group by
b.idindustria,
b.fantasia
order by fq desc,media desc";




$result = pg_query($query);

$nivel3 = '<i class="fa fa-star" style="color:#FFC107;" aria-hidden="true"><span class="fq">3</span></i>';
$nivel2 = '<i class="fa fa-star" style="color:#a0a0a0;" aria-hidden="true"><span class="fq">2</span></i>';
$nivel1 = '<i class="fa fa-star" style="color:#af592e;" aria-hidden="true"><span class="fq">1</span></i>';
$nivel0 = '<i class="fa fa-star" style="color:#000000;" aria-hidden="true"><span class="fq">0</span></i>';

while ($row = pg_fetch_array($result)) {

    switch ($row['fq']) {
        case 0: $nivel0++;
            break;
        case 1: $nivel1++;
            break;
        case 2: $nivel2++;
            break;
        case 3: $nivel3++;
            break;
    }


    echo "<tr>";
    // echo '<td> ' . $row['idindustria'] . '</td>';
    echo '<td class="alinhamento" width=160>&nbsp;&nbsp;' . $row['fantasia'] . '</td>';
    switch ($row['fq']) {
        case 0: echo '<td width=15>' . $nivel0 . '</td>';
            break;
        case 1: echo '<td width=15>' . $nivel1 . '</td>';
            break;
        case 2: echo '<td width=15>' . $nivel2 . '</td>';
            break;
        case 3: echo '<td width=15>' . $nivel3 . '</td>';
            break;
    }

    echo '<td width=60>&nbsp;&nbsp;' . number_format($row['media'], 0, ',', '.') . '</td>';
    echo '<td width=60>&nbsp;&nbsp;' . number_format($row['mes1'], 0, ',', '.') . '</td>';
    echo '<td width=40 align="center"><button data-toggle="modal" data-target="#janela"  type="button" name="teste" class="btn_detalhar" data-id_industria="'.$row['idindustria'].'"><i class="fa fa-table" style="color:#005f8a;" aria-hidden="true"></i></button></td>';
    echo "</tr>";
}
?>


<script>
    $(document).ready(function () {
        $('.btn_detalhar').click(function () {
            var idindustria=$(this).data('id_industria');
            var iddocliente = $("#selectCliente").val();
           // alert(iddocliente)
            var rca = $(".select_rca").val();
            $.post('classes/sql_modalprodutos.php',{industria:idindustria,cliente:iddocliente,rca:rca},function(data){
                       $("#vendaproduto").empty();
                        $("#vendaproduto").html(data);
            })
        });

    })
</script>
 


<script src="../js/jquery-3.2.1.js"></script>
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
<script src="js/graficoSellout.js"></script>


