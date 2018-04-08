<?php

phpinfo();
echo 'Tentando conexao!';
if (!@($conexao = pg_connect("host=187.72.34.18 dbname=worklistgo port=5432 user=df password=tidf123"))) {
    print "Não foi possível estabelecer uma conexão com o banco de dados.";
}

echo 'Tentando conexao!';


$query = "select count(idcliente) as carteira from sys_carteira where idvendedor =717";
$result = pg_query($query);
$carteira = pg_fetch_array($result);
echo $carteira['carteira'];
