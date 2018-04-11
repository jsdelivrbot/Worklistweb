<?php

// chamar criador de sessão de login
if (!isset($_SESSION)) {
    session_start();
}
if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

$idindustria = $_POST['industria'];
$idcliente = $_POST['cliente'];

!@($conexao = pg_connect("host=187.72.34.18 dbname=worklistgo port=5432 user=df password=tidf123"));
$query = "SELECT
  b.idproduto,
  b.produto,
  ROUND(SUM(qmes5),0) AS QMES5,
  ROUND(SUM(qmes4),0) AS QMES4,
  ROUND(SUM(qmes3),0) AS QMES3,
  ROUND(SUM(qmes2),0) AS QMES2,
  ROUND(SUM(qmes1),0) AS QMES1,
  ROUND(SUM(qmes5)+SUM(qmes4)+SUM(qmes3)+SUM(qmes2)+SUM(qmes1),0) AS TOTAL
  FROM
      sys_vendas A, sys_produtos B
  WHERE
	A.idproduto = B.idproduto AND ";
if ($idcliente <> 1) {
    $query .= " A.idcliente = $idcliente AND";
}

$query .= " A.idvendedor IN (" . $_SESSION['idrepresentante'] . ")";
if ($idindustria <> 1) {
    $query .= " and a.idindustria IN(0," . $idindustria . ")";
}
$query .= " and a.canalvendas in('VENDEDOR')
GROUP BY   b.idproduto,  b.produto
ORDER BY TOTAL DESC";
$aux=$query;
$query = pg_query($query);
?>
<script>
    $("#pesquisaproduto").keyup(function () {
        var index = $(this).parent().index();
        var nth = "#tableProd td:nth-child(" + (index + 1).toString() + ")";
        var valor = $(this).val().toUpperCase();
        $("#tableProd tbody tr").show();
        $(nth).each(function () {
            if ($(this).text().toUpperCase().indexOf(valor) < 0) {
                $(this).parent().hide();
            }
        });
    });

    $("#pesquisaproduto").blur(function () {
        $(this).val("");
    });
</script>
<div >
    <input type="text" id="pesquisaproduto" placeholder="Pesquisar Produtos" style="width: 98%;height: 23px;margin-left: 2%;">
</div>  

<?php

require_once '../funcoes/funcoes.php';
echo "<table class='table table-bordered dataTable no-footer' id='tableProd' width='100%' cellspacing='0'>
       <thead>
        <tr class='cabecalho_indicador'>
             <th>PRODUTO</th>";
echo "<th align = 'center' class='ajusteth'>" . $_SESSION['MES5'] . "</th>";
echo "<th align = 'center' class='ajusteth'>" . $_SESSION['MES4'] . "</th>";
echo "<th align = 'center' class='ajusteth'>" . $_SESSION['MES3'] . "</th>";
echo "<th align = 'center' class='ajusteth'>" . $_SESSION['MES2'] . "</th>";
echo "<th align = 'center' class='ajusteth'>" . $_SESSION['MES1'] . "</th>";
echo "</tr>
       </thead>
       <tbody>";
while ($row = pg_fetch_array($query)) {
    echo "<tr>";
    //echo "<td>&nbsp" . $row['idproduto'] . "</td>";
    echo "<td>&nbsp&nbsp" . $row['produto'] . "</td>";
    defineCorCliPositivado($row['qmes5']);
    defineCorCliPositivado($row['qmes4']);
    defineCorCliPositivado($row['qmes3']);
    defineCorCliPositivado($row['qmes2']);
    defineCorCliPositivado($row['qmes1']);
    echo "</tr>";
}
echo "</tbody>";
echo "</table>";

//montando a query para execução no banco
$query = "insert into sys_controleacesso (idusuario,dataacesso,telaacessada) values (".$_SESSION['idrepresentante'].",current_timestamp,'produtos')";

//executando a query montada acima
$result = pg_query($query);


?>
