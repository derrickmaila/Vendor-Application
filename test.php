<?php
$db = new PDO('mysql:host=m2dev-db-ws-001.c9dvmnpdpl1l.us-east-1.rds.amazonaws.com;dbname=ws_iliadportal;charset=utf8', 'iliadportal', 'iLI@d9otR@l');
$sql = "select * from users where control = 533";
$statement = $db->prepare($sql);
$statement->execute();
print_r($statement->fetch(PDO::FETCH_OBJ));

?>