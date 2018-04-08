<?php

// Worklist Goiás Saúde - Pagina ajustada para a nova estrutura de supervisão.
// chamar criador de sessão de login
if(!isset($_SESSION))

{

session_start();

}

if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}

$dtini = $_POST['dtini'];
$dtfim = $_POST['dtfim'];
//require_once 'funcoes/funcoes.php';
require_once 'bd.php';
require_once '../funcoes/funcoes.php';
$bd = new bd();

$query = "select
a.idrca,
a.rca,
sum(a.pedidos) as pedidos,
sum(a.dn) as dn,
sum(a.total) as total
from
------------------ tabela de pedidos --------------------
(select
b.idvendedor as idrca,
b.apelido as rca,
count(distinct pedido) as pedidos,
count(distinct idcli) as dn,
sum(total) as total
from
sys_pedidos a,
sys_vendedores b,
sys_acessos c
where
a.id_rca = b.idvendedor and
a.id_rca = c.idrepresentante and
a.id_rca in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) "; 
$query .= " and a.mes >= '$dtini' and a.mes <= '$dtfim'
group by
b.idvendedor,
b.apelido
------------------- tabela de vendedores ---------------
UNION ALL 
select
b.idvendedor as idrca,
b.apelido as rca,
0 as pedidos,
0 as dn,
0 as total
from
sys_acessos a,
sys_vendedores b
where
a.idrepresentante = b.idvendedor and
a.idrepresentante in (select idrca from sys_supervisao where idsupervisor = ".$_SESSION['idrepresentante']." order by idrca) )a ";  
$query .= " group by
a.idrca,
a.rca ORDER BY TOTAL DESC";
    

$result = pg_query($query);

    
while ($row = pg_fetch_array($result)) {
echo "<tr>";
echo '<td  style="font-weight:bold;"> ' . $row['idrca'] . '</td>';
echo '<td  style="font-weight:bold;"> ' . $row['rca'] . '</td>';
echo defineCorCliPositivado($row['pedidos']);
echo defineCorCliPositivado($row['dn']);
echo CorPedidos($row['total']);
echo "</tr>";
}
?>