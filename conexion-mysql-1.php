<?php
$host='localhost';
$user='est_distrisuper';
$db='est_distrisuper';
$pwd='as22k4x9hb8';
//$host='192.168.0.195';
//$user='distrisuper';
//$db='distrisuper';
//$pwd='4132';
$db_mysql=mysqli_connect($host, $user, $pwd, $db);
//$db_mysql=mysql_select_db($db, $con);
mysqli_query($db_mysql,'SET NAMES \'utf8\'');
?>
