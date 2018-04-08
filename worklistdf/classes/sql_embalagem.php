<?php
// chamar criador de sessão de login
if(!isset($_SESSION))

{

session_start();

}


if ($_SESSION['logado'] != "SIM") {
    header('Location: ../login.php');
}


$barra=$_POST['barra'];

//require_once 'funcoes/funcoes.php';
require_once 'bd.php';

$bd = new bd();

$result = pg_query("select * from sys_embalagem where codigo_barras='$barra'");
$row = pg_fetch_array($result);

?>

<tr>
    <td>Codigo do Produto</td>
    <td><strong><?php echo $row['codigo_barras']?></strong></td>
</tr>
<tr>
    <td>Produto</td>
    <td><strong><?php echo $row['produto']?></strong></td>
</tr>
<tr>
    <td>Altura</td>
    <td><strong><?php echo $row['altura']?></strong></td>
</tr>

<tr>
    <td>Largura</td>
    <td><strong><?php echo $row['largura']?></strong></td>
</tr>
<tr>
    <td>Comprimento</td>
    <td><strong><?php echo $row['comprimento']?></strong></td>
</tr>
<tr>
    <td>Lastro</td>
    <td><strong><?php echo $row['lastro']?></strong></td>
</tr>
<tr>
    <td>Camadas</td>
    <td><strong><?php echo $row['qtdecamada']?></strong></td>
</tr>
<tr>
    <td>Peso</td>
    <td><strong><?php echo $row['pesobruto']?></strong></td>
</tr>




