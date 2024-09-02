<?php
//$host_principal_dimes="181.230.201.84:F:\\Flexxus\\DB\\DB-DIMERSA.GDB";
//$host_ds_dimes="181.230.201.84:F:\\Flexxus\\DB\\DBSUCURSAL-DIMERSA.GDB";
//$host_principal_dimes="201.235.250.245:F:\\Flexxus\\DB\\DB-DIMERSA.GDB";
//$host_ds_dimes="201.235.250.245:F:\\Flexxus\\DB\\DBSUCURSAL-DIMERSA.GDB";
//$host_principal="201.235.206.195:F:\\Flexxus\\DB\\DB-DIMERSA.GDB";
//$host_ds="201.235.206.195:F:\\Flexxus\\DB\\DBSUCURSAL-DIMERSA.GDB";
//$host_principal_dimes="190.189.99.151:F:\\Flexxus\\DB\\DB-DIMERSA.GDB";
//$host_ds_dimes="190.189.99.151:F:\\Flexxus\\DB\\DBSUCURSAL-DIMERSA.GDB";

// 201.178.224.61

//$host_principal_dimes="distrimdp.dvrdns.org:F:\\Flexxus\\DB\\DB-DIMERSA.GDB";
//$host_ds_dimes="distrimdp.dvrdns.org:F:\\Flexxus\\DB\\DBSUCURSAL-DIMERSA.GDB";
$host_principal="distrimdp.dvrdns.org:E:\\DB\\DB-DIMERSA.GDB";
$host_ds="distrimdp.dvrdns.org:E:\\DB\\DBSUCURSAL-DIMERSA.GDB";

$user_dimes="SYSDBA";
//$pwd="3122414422";
$pwd_dimes="31224144222";
$db_principal_dimes = ibase_connect($host_principal_dimes, $user_dimes, $pwd_dimes);
$db_ds_dimes = ibase_connect($host_ds_dimes, $user_dimes, $pwd_dimes);
?>
