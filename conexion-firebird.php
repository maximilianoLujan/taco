<?php
$host_principal="170.254.205.41:C:\\FLEXXUS\\DB\\DB-DISTRISUPER.GDB";
//$host_ds="200.5.105.82:C:\\FLEXXUS\\DB\\DBSUCURSAL-DISTRISUPER.GDB";
$host_ds="170.254.205.41:C:\\FLEXXUS\\DB\\DBSUCURSAL-DISTRISUPER.GDB";
$user="SYSDBA";
$pwd="31224144222";
$db_principal=ibase_connect($host_principal, $user, $pwd);
$db_ds=ibase_connect($host_ds, $user, $pwd);
?>
