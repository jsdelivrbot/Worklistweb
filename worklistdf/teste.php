<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 
<head> 
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /> 
<title>Import a CSV File with PHP & MySQL</title> 
</head> 

<body> 

<?php if (!empty($_GET[success])) { echo "<b>Your file has been imported.</b><br><br>"; } //generic success notice ?> 

  <form action="" method="post" enctype="multipart/form-data" name="form1" id="form1"> 
    Choose your file: <br /> 
    <input name="csv" type="file" id="csv" /> 
    <input type="submit" name="Submit" value="Submit" /> 
  </form> 
  <form action="" method="post" enctype="multipart/form-data" name="form2" id="form2"> 
    <label>Selecione o status:</label>
    <select name="changePed">
      <option value="separacao">Em Separação</option>
      <option value="cancelado">Cancelado</option>
      <option value="faturado">Faturado</option>
      <option value="exp">Expedido</option>
  </select>
    <input type="submit" value="Alterar">
  </form>
<?php


echo "<table border='1'>
<tr>
<th><input type='checkbox' name='select-all' id='select-all' /></th>
<th>Data de emissão</th>
<th>EMS</th>
<th>Pedido do  cliente</th>
<th>Cliente</th>
<th>Valor do pedido</th>
<th>Status</th>
</tr>";
$num = 0;

while($num <= 10)
{
  echo "<tr>";
  echo "<td><input name='checkbox[]' type='checkbox'></td>";
  echo "<td>aa" . $num . "</td>";
  echo "<td>bb" . $num . "</td>";
  echo "<td>cc" . $num . "</td>";
  echo "<td>dd" . $num . "</td>";
  echo "<td>ee" . $num. "</td>";
  echo "<td>ff" . $num . "</td>";
  echo "</tr>";
  $num++;
}
echo "</table>";
?>
