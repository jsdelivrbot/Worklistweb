<?php
  try{
      // Conecta com o MySQL
      $con = mysqli_connect("localhost", "root", "", "plot_db");
      // Query
      $con->real_query("SELECT count(*) as votos, problema FROM plot_db.enquete group by problema");
      $query = $con->store_result();
      // Array
      $data = array('votos' => [] , 'problema' => []);
      // Associando dados
      while ($row = $query->fetch_assoc()) {
        array_push($data['votos'], $row['votos']);
        array_push($data['problema'], utf8_encode($row['problema']));
      }
      // Encerra a conexao
      $con->close();
      //json_encode
      echo json_encode($data);
   } catch (\Exception $e){
      var_dump($e);
      die();
   }

?>