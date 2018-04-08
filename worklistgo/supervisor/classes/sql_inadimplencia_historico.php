<?php
// Worklist Goiás Saúde - Pagina ajustada para a nova estrutura de supevisão.
// chamar criador de sessão de login
if (!isset($_SESSION)) {
    session_start();
}

if ($_SESSION['logado'] != "SIM") {
    header('Location: login.php');
}

if (isset($_POST['idcliente'])) {
    $idcliente = $_POST['idcliente'];
} else {
    $idcliente = 1;
}
$rca = $_POST['rca'];

require_once '../funcoes/funcoes.php';
require_once 'bd.php';
$bd = new bd();
$query = "    
    SELECT 
    SUM(a.valor) as vencido
    FROM 
    sys_inadimplencia a,
    sys_acessos b
    WHERE
    a.idvendedor=b.idrepresentante and
    a.STATUS = 'VENCIDO'";
if ($rca == 1) {
    $query.="  and a.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $query.=" and a.idvendedor = $rca";
}

if ($idcliente <> 1) {
    $query .= " and a.idcli IN($idcliente)  ";
}

//executando a query montada acima
$result = pg_query($query);

$row = pg_fetch_array($result);

$vencido = $row['vencido'];


$query = "
    SELECT 
    SUM(a.valor) as carteira
    FROM 
    sys_inadimplencia a,
    sys_acessos b
    WHERE 
    a.idvendedor=b.idrepresentante";
if ($rca == 1) {
    $query.="  and a.idvendedor in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) ";
} else {
    $query.=" and a.idvendedor = $rca";
}

if ($idcliente <> 1) {
    $query .= " and a.idcli IN($idcliente)  ";
}

//executando a query montada acima
$result = pg_query($query);

$row = pg_fetch_array($result);

$carteira = $row['carteira'];



echo'  <canvas id="graficoinadimplencia" width="100" height="50"></canvas>';
?>

<script>
    var carteira = <?php echo $carteira; ?>;
    var vencido = <?php echo $vencido; ?>;
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
<script src="js/graficoInadimplencia_historico.js"></script>
