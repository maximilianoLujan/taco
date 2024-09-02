<?php
// $host_redis='vps133808.conectemos.com';
// $user_redis='saasnet';
// $db_redis='admin_pedir';
// $pwd_redis='Campeon06';

$host_redis='51.222.158.198';
$user_redis='lucas';
$db_redis='admin_pedir';
$pwd_redis='Integracion123**';

$db_redis=mysqli_connect($host_redis, $user_redis, $pwd_redis, $db_redis);
//$db_mysql=mysql_select_db($db, $con);
mysqli_query($db_redis,'SET NAMES \'utf8\'');
?>
