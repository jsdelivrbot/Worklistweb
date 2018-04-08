<?php
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

require_once '../funcoes/funcoes.php';
require_once 'bd.php';
$bd = new bd();
$query = "
    SELECT 
    SUM(a.valor) as vencido
    FROM 
    sys_inadimplencia a 
    WHERE 
    a.STATUS = 'VENCIDO' AND a.tipo NOT IN ('CR') AND a.portador NOT IN ('0998') and
    a.idrca = " . $_SESSION['idrepresentante'];
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
    sys_inadimplencia a 
    WHERE 
    a.tipo NOT IN ('CR') AND a.portador NOT IN ('0998') and
    a.idrca = " . $_SESSION['idrepresentante'];
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
