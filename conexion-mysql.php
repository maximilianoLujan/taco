<?php
//Esto es produccion distri
$host='distrisuper.ddns.net';
$user='ext_distrisuper';
$db='est_distrisuper';
$pwd='9t9say65.xt8_';

//esto es produccion dimes
// $host='dimes.ddns.net';
// $user='ext_distrisuper';
// $db='dimes';
// $pwd='9t9say65.xt8_';


//Esto es local
//$host='127.0.0.1';
//$user='root';
//$db='est_distrisuper';
//$pwd='';
$db_mysql=mysqli_connect($host, $user, $pwd, $db);
//$db_mysql=mysql_select_db($db, $con);
mysqli_query($db_mysql,'SET NAMES \'utf8\'');
?>
