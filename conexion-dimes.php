<?php
$host_dimes='dimes.ddns.net';
$user_dimes='ext_distrisuper';
$db_dimes='dimes';
$pwd_dimes='9t9say65.xt8_';
//$con_dimes=mysql_connect($host_dimes, $user_dimes, $pwd_dimes);
//$db_mysql_dimes=mysql_select_db($db_dimes, $con_dimes);
//mysql_query('SET NAMES \'utf8\'');
$db_mysql_dimes=mysqli_connect($host_dimes, $user_dimes, $pwd_dimes, $db_dimes);
//$db_mysql=mysql_select_db($db, $con);
mysqli_query($db_mysql_dimes,'SET NAMES \'utf8\'');
?>
