<?php

function clientes_activos(){
	global $db_mysql;
	$sql="SELECT count(`id_cliente`) as `activos` FROM `clientes` WHERE `activo_taco` = 1;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		return intval($dat['activos']);
	}
	return 0;
}

function ultima_importacion(){
	global $db_mysql;
	$sql="SELECT `fecha` FROM `ventas` ORDER BY `fecha` DESC LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		return $dat['fecha'];
	}else{
		return false;
	}
}

function calcular_ventas($sucursal,$campo_fecha,$tipo){
	global $db_mysql;
	$campo = array('cantidad' => 0, 'monto' => 0);
	$consulta="SELECT SUM(`cantidad`) as `cantidad`, SUM(`monto_total`) as `monto` FROM `ventas`
	LEFT JOIN `sucursales` ON `ventas`.`id_sucursal` = `sucursales`.`id_sucursal`";
	if( $tipo=='Años' ){
		$array_condiciones[] = '`ventas`.`ano` = \'' . intval($campo_fecha) . '\'';
	}elseif( $tipo=='Trimestre' ){
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$trimestre=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ventas`.`ano` = \'' . $ano . '\'';
			$array_condiciones[] = '`ventas`.`trimestre` = \'' . $trimestre . '\'';
		}
	}else{
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$mes=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ventas`.`ano` = \'' . $ano . '\'';
			$array_condiciones[] = '`ventas`.`mes` = \'' . $mes . '\'';
		}
	}
	if($sucursal=='Pico'){
		$array_condiciones[] = '`ventas`.`id_sucursal` = \'1\'';
	}elseif($sucursal=='MDP'){
		$array_condiciones[] = '`ventas`.`id_sucursal` = \'3\'';
	}
	if(count(@$array_condiciones)){
		$condiciones = ' WHERE `ventas`.`anulada` = \'0\' AND ' . implode( ' AND ' , $array_condiciones );
	}else{
		$condiciones = '';
	}
	$sql="$consulta $condiciones;";
//	echo "<p>$sql</p>";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		$campo = array('cantidad'=>round($dat['cantidad']),'monto'=>round($dat['monto']));
	}
	return @$campo;
}

function calcular_costos_variables($sucursal,$campo_fecha,$tipo){
	global $db_mysql;
	$campo = 0;
	$consulta="SELECT SUM(`costo_venta`) as `costo` FROM `ventas`";
	if( $tipo=='Años' ){
		$array_condiciones[] = '`ano` = \'' . intval($campo_fecha) . '\'';
	}elseif( $tipo=='Trimestre' ){
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$trimestre=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
			$array_condiciones[] = '`trimestre` = \'' . $trimestre . '\'';
		}
	}else{
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$mes=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
			$array_condiciones[] = '`mes` = \'' . $mes . '\'';
		}
	}
	if($sucursal=='Pico'){
		$array_condiciones[] = '`id_sucursal` = \'1\'';
	}elseif($sucursal=='MDP'){
		$array_condiciones[] = '`id_sucursal` = \'3\'';
	}
	if(count(@$array_condiciones)){
		$condiciones = ' WHERE `anulada` = \'0\' AND ' . implode( ' AND ' , $array_condiciones );
	}else{
		$condiciones = '';
	}
	$sql="$consulta $condiciones;";
//	echo "<p>$sql</p>";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		$campo = round($dat['costo']);
	}
	return $campo;
}

function calcular_costos_fijos($sucursal,$campo_fecha,$tipo){
	global $db_mysql;
	$campo = 0;
	$consulta="SELECT SUM(`total`) as `costo` FROM `costos_fijos`";
	if( $tipo=='Años' ){
		$array_condiciones[] = '`ano` = \'' . intval($campo_fecha) . '\'';
	}elseif( $tipo=='Trimestre' ){
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$trimestre=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
			$array_condiciones[] = '`trimestre` = \'' . $trimestre . '\'';
		}
	}else{
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$mes=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
			$array_condiciones[] = '`mes` = \'' . $mes . '\'';
		}
	}
	if($sucursal=='Pico'){
		$array_condiciones[] = '`codigocentrocosto` = \'1\'';
	}elseif($sucursal=='MDP'){
		$array_condiciones[] = '`codigocentrocosto` = \'3\'';
	}
	if(count(@$array_condiciones)){
		$condiciones = ' WHERE `anulacion_manual` = \'0\' AND `es_ppp` = \'0\' AND `cheque_rechazado` = \'0\' AND ' . implode( ' AND ' , $array_condiciones );
	}else{
		$condiciones = '';
	}
	$sql="$consulta $condiciones;";
//	echo "<p>$sql</p>";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		$campo = round($dat['costo']);
	}
	return $campo;
}


function calcular_stock($sucursal,$campo_fecha,$tipo){
	global $db_mysql;
	$campo = array('monto' => 0,'unidades' => 0);
	$consulta="SELECT SUM(`monto_total`) as `monto`, SUM(`unidades`) as `unidades` FROM `stock`";
	if( $tipo=='Años' ){
		$mes=intval(date('m'));
		$array_condiciones[] = '`ano` = \'' . intval($campo_fecha) . '\'';
		$array_condiciones[] = '`mes` = \'' . $mes . '\'';
	}elseif( $tipo=='Trimestre' ){
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$trimestre=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
//			$array_condiciones[] = '`trimestre` = \'' . $trimestre . '\'';
			$array_condiciones[] = '`mes` = \'' . 3 * $trimestre . '\'';
		}
	}else{
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$mes=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
			$array_condiciones[] = '`mes` = \'' . $mes . '\'';
		}
	}
	if($sucursal=='Pico'){
		$array_condiciones[] = '`codigodeposito` = \'1\'';
	}elseif($sucursal=='MDP'){
		$array_condiciones[] = '`codigodeposito` = \'3\'';
	}
	if(count(@$array_condiciones)){
		$condiciones = ' WHERE ' . implode( ' AND ' , $array_condiciones );
	}else{
		$condiciones = '';
	}
	$sql="$consulta $condiciones;";
//	echo "<p>$sql</p>";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		$campo = array('monto'=>round($dat['monto']),'unidades'=>round($dat['unidades']));
	}
	return @$campo;
}

function calcular_rentabilidad($sucursal,$fecha,$periodo){
	$ventas = calcular_ventas($sucursal,$fecha,$periodo);
	$monto_ventas = round($ventas['monto']);
	$costos_variables = round(calcular_costos_variables($sucursal,$fecha,$periodo));
	$costos_fijos = round(calcular_costos_fijos($sucursal,$fecha,$periodo));
	return $monto_ventas - $costos_variables - $costos_fijos;
}

function saldo_caja($fecha,$tipo){
	global $db_mysql;
	if( $tipo=='Años' ){
		$ano = $fecha;
		$mes = 12;
	}elseif( $tipo=='Trimestre' ){
		$array_fecha = explode('-',$fecha);
		$ano = $array_fecha[0];
		$mes = $array_fecha[1] * 3;
	}else{
		$array_fecha = explode('-',$fecha);
		$ano = $array_fecha[0];
		$mes = $array_fecha[1];
	}
	$sql="SELECT SUM(`monto`) as `total` FROM `saldos finales` WHERE `ano` = '$ano' AND `mes` = '$mes' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		return $dat['total'];
	}
	return 0;
}

function array_caja($fechas,$tipo){
	foreach($fechas as $f){
		$total_caja[] = saldo_caja($f,$tipo);
	}
	return $total_caja;
}


function ingreso_total_periodo($fecha){
	global $db_mysql;
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT SUM(`total`) as `monto` FROM `ingresos` WHERE `ano` = '$ano' AND `mes` = '$mes' AND `anulada` = '0' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		return $dat['monto'];
	}else{
		return 0;
	}
}
function egreso_total_periodo($fecha){
	global $db_mysql;
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT SUM(`total`) as `monto` FROM `egresos` WHERE `ano` = '$ano' AND `mes` = '$mes' AND `anulada` = '0' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		return $dat['monto'];
	}else{
		return 0;
	}
}

function ingresos_egresos_principal($fecha,$tipo){
	global $db_mysql;
	$ingresos = 0;
	$egresos = 0;
	if( $tipo=='Años' ){
		$ano = $fecha;
		$sql_ing="SELECT SUM(`total`) as `monto` FROM `ingresos` WHERE `ano` = '$ano' AND `id_base` = '1' AND `anulada` = '0' LIMIT 1;";
		$sql_egr="SELECT SUM(`total`) as `monto` FROM `egresos` WHERE `ano` = '$ano' AND `id_base` = '1' AND `anulada` = '0' LIMIT 1;";
	}elseif( $tipo=='Trimestre' ){
		$array_fecha = explode('-',$fecha);
		$ano = $array_fecha[0];
		$trimestre = $array_fecha[1];
		$sql_ing="SELECT SUM(`total`) as `monto` FROM `ingresos` WHERE `ano` = '$ano' AND `trimestre` = '$trimestre' AND `id_base` = '1' AND `anulada` = '0' LIMIT 1;";
		$sql_egr="SELECT SUM(`total`) as `monto` FROM `egresos` WHERE `ano` = '$ano' AND `trimestre` = '$trimestre' AND `id_base` = '1' AND `anulada` = '0' LIMIT 1;";
	}else{
		$array_fecha = explode('-',$fecha);
		$ano = $array_fecha[0];
		$mes = $array_fecha[1];
		$sql_ing="SELECT SUM(`total`) as `monto` FROM `ingresos` WHERE `ano` = '$ano' AND `mes` = '$mes' AND `id_base` = '1' AND `anulada` = '0' LIMIT 1;";
		$sql_egr="SELECT SUM(`total`) as `monto` FROM `egresos` WHERE `ano` = '$ano' AND `mes` = '$mes' AND `id_base` = '1' AND `anulada` = '0' LIMIT 1;";
	}
	$result=mysqli_query($db_mysql,$sql_ing);
	if($dat=mysqli_fetch_array($result)){
		$ingresos = $dat['monto'];
	}
	$result=mysqli_query($db_mysql,$sql_egr);
	if($dat=mysqli_fetch_array($result)){
		$egresos = $dat['monto'];
	}
	return floatval($ingresos-$egresos);
}

/* copia exacta de trimestre_anterior(), periodo_anterior(), etc. Porque ahún no se ha cargado */

function trimestre_anterior_parche($trimestre){
	$array_trimestre = explode('-',$trimestre);
	if( count($array_trimestre)==2 ){
		$y = intval($array_trimestre[0]);
		$q = intval($array_trimestre[1]);
		$q--;
		if($q<=0){
			$q=4;
			$y--;
		}
		return $y . '-' . $q;
	}else{
		return false;
	}
}
function mes_anterior_parche($fecha){
	$time=strtotime($fecha);
	$nuevafecha=strtotime('-1 month',$time);
	return date('Y-n',$nuevafecha);
}
function ano_anterior_parche($fecha){
	$int_fecha=intval($fecha);
	$int_fecha--;
	return $int_fecha;
}
function periodo_anterior_parche( $periodo_actual, $tipo ){ 
	if( $tipo=='Años' ){
		return ano_anterior_parche($periodo_actual);
	}elseif( $tipo=='Trimestre' ){
		return trimestre_anterior_parche($periodo_actual);
	}else{
		return mes_anterior_parche($periodo_actual);
	}
}
/* fin parche */

function calcular_rentabilidad_stock($sucursal,$fecha,$periodo){
	$ventas = calcular_ventas($sucursal,$fecha,$periodo);
	$monto_ventas = round($ventas['monto']);
	$costos_variables = round(calcular_costos_variables($sucursal,$fecha,$periodo));
//	$costos_fijos = round(calcular_costos_fijos($sucursal,$fecha,$periodo));
	$stock_f = calcular_stock($sucursal,$fecha,$periodo);
	$fecha_i = periodo_anterior_parche( $fecha, $periodo);
	$stock_i = calcular_stock($sucursal,$fecha_i,$periodo);
	return ($monto_ventas - $costos_variables) + ($stock_f['monto'] - $stock_i['monto']);
}
function calcular_rentabilidad_stock_sv($sucursal,$fecha,$periodo){
	$ventas = calcular_ventas($sucursal,$fecha,$periodo);
	$rentabilidad = calcular_rentabilidad_stock($sucursal,$fecha,$periodo);
	if( $ventas['monto'] != 0 ){
		return floatval( 100 * ($rentabilidad/$ventas['monto']) );
	}
	return floatval(0);
}
function calcular_rentabilidad_sv($sucursal,$fecha,$periodo){
	$ventas = calcular_ventas($sucursal,$fecha,$periodo);
	$rentabilidad = calcular_rentabilidad($sucursal,$fecha,$periodo);
	if( $ventas['monto'] != 0 ){
		return floatval( 100 * ($rentabilidad/$ventas['monto']) );
	}
	return floatval(0);
}
function calcular_deudas_clientes($sucursal,$campo_fecha,$tipo){
	global $db_mysql;
	$campo = 0;
	$consulta="SELECT SUM(`deuda`) as `deuda` FROM `deudas_clientes`";
	if( $tipo=='Años' ){
		$mes=intval(date('m'));
		$array_condiciones[] = '`ano` = \'' . intval($campo_fecha) . '\'';
		$array_condiciones[] = '`mes` = \'' . $mes . '\'';
	}elseif( $tipo=='Trimestre' ){
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$trimestre=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
//			$array_condiciones[] = '`trimestre` = \'' . $trimestre . '\'';
			$array_condiciones[] = '`mes` = \'' . 3 * $trimestre . '\'';
		}
	}else{
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$mes=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
			$array_condiciones[] = '`mes` = \'' . $mes . '\'';
		}
	}
	if($sucursal=='Pico'){
		$array_condiciones[] = '`codigo_deposito` = \'1\'';
	}elseif($sucursal=='MDP'){
		$array_condiciones[] = '`codigo_deposito` = \'3\'';
	}
	if(count(@$array_condiciones)){
		$condiciones = ' WHERE ' . implode( ' AND ' , $array_condiciones );
	}else{
		$condiciones = '';
	}
	$sql="$consulta $condiciones;";
//	echo "<p>$sql</p>";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		$campo = round($dat['deuda']);
	}
	return @$campo;
}

function calcular_deudas_proveedores($campo_fecha,$tipo){
	global $db_mysql;
	$campo = 0;
	$consulta="SELECT SUM(`deuda`) as `deuda` FROM `deudas_proveedores`";
	if( $tipo=='Años' ){
		$mes=intval(date('m'));
		$array_condiciones[] = '`ano` = \'' . intval($campo_fecha) . '\'';
		$array_condiciones[] = '`mes` = \'' . $mes . '\'';
	}elseif( $tipo=='Trimestre' ){
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$trimestre=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
//			$array_condiciones[] = '`trimestre` = \'' . $trimestre . '\'';
			$array_condiciones[] = '`mes` = \'' . 3 * $trimestre . '\'';
		}
	}else{
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$mes=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
			$array_condiciones[] = '`mes` = \'' . $mes . '\'';
		}
	}
	if(count(@$array_condiciones)){
		$condiciones = ' WHERE ' . implode( ' AND ' , $array_condiciones );
	}else{
		$condiciones = '';
	}
	$sql="$consulta $condiciones;";
//	echo "<p>$sql</p>";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		$campo = round($dat['deuda']);
	}
	return @$campo;
}

function calcular_cobranzas_total($campo_fecha,$tipo){
	global $db_mysql;
	$campo = 0;
	$consulta="SELECT SUM(`total`) as `total` FROM `cobranzas`";
	if( $tipo=='Años' ){
		$mes=intval(date('m'));
		$array_condiciones[] = '`ano` = \'' . intval($campo_fecha) . '\'';
		$array_condiciones[] = '`mes` = \'' . $mes . '\'';
	}elseif( $tipo=='Trimestre' ){
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$trimestre=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
			$array_condiciones[] = '`trimestre` = \'' . $trimestre . '\'';
		}
	}else{
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$mes=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
			$array_condiciones[] = '`mes` = \'' . $mes . '\'';
		}
	}
	if(count(@$array_condiciones)){
		$condiciones = ' WHERE ' . implode( ' AND ' , $array_condiciones );
	}else{
		$condiciones = '';
	}
	$sql="$consulta $condiciones;";
//	echo "<p>$sql</p>";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		$campo = round($dat['total']);
	}
	return @$campo;
}

function calcular_r_total_cobranzas($campo_fecha,$tipo){
	global $db_mysql;
	$campo = 0;
	$consulta="SELECT SUM(`rentabilidad_total`) as `rentabilidad_total` FROM `cobranzas`";
	if( $tipo=='Años' ){
		$mes=intval(date('m'));
		$array_condiciones[] = '`ano` = \'' . intval($campo_fecha) . '\'';
		$array_condiciones[] = '`mes` = \'' . $mes . '\'';
	}elseif( $tipo=='Trimestre' ){
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$trimestre=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
			$array_condiciones[] = '`trimestre` = \'' . $trimestre . '\'';
		}
	}else{
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$mes=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
			$array_condiciones[] = '`mes` = \'' . $mes . '\'';
		}
	}
	if(count(@$array_condiciones)){
		$condiciones = ' WHERE ' . implode( ' AND ' , $array_condiciones );
	}else{
		$condiciones = '';
	}
	$sql="$consulta $condiciones;";
//	echo "<p>$sql</p>";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		$campo = round($dat['rentabilidad_total']);
	}
	return @$campo;
}

function calcular_r_porcentaje_cobranzas($campo_fecha,$tipo){
	global $db_mysql;
	$campo = 1;
	$consulta="SELECT SUM(`rentabilidad_total`) as `rentabilidad_total`, SUM(`total`) as `cobranza_total` FROM `cobranzas`";
	if( $tipo=='Años' ){
		$mes=intval(date('m'));
		$array_condiciones[] = '`ano` = \'' . intval($campo_fecha) . '\'';
		$array_condiciones[] = '`mes` = \'' . $mes . '\'';
	}elseif( $tipo=='Trimestre' ){
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$trimestre=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
			$array_condiciones[] = '`trimestre` = \'' . $trimestre . '\'';
		}
	}else{
		$array_fecha=explode('-',$campo_fecha);
		$ano=intval($array_fecha[0]);
		$mes=intval($array_fecha[1]);
		if(count($array_fecha)==2){
			$array_condiciones[] = '`ano` = \'' . $ano . '\'';
			$array_condiciones[] = '`mes` = \'' . $mes . '\'';
		}
	}
	if(count(@$array_condiciones)){
		$condiciones = ' WHERE ' . implode( ' AND ' , $array_condiciones );
	}else{
		$condiciones = '';
	}
	$sql="$consulta $condiciones;";
//	echo "<p>$sql</p>";
	$result=mysqli_query($db_mysql,$sql);
	if( $dat=mysqli_fetch_array($result) ){
		if( floatval($dat['cobranza_total']) != 0 ){
			$campo = floatval(100) * floatval($dat['rentabilidad_total']) / floatval($dat['cobranza_total']);
		}
	}
	return @$campo;
}

function buscar_en_cache($busqueda){
	global $db_mysql;
	$sql="SELECT `html`, `timestamp` FROM `cache` WHERE `clave` = '$busqueda' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if( $dat=mysqli_fetch_array($result) ){
		return $dat['html'];
	}else{
		return false;
	}
}

function borrar_de_cache(){
	global $db_mysql;
	$tiempo = time() - 43200;//12 horas
	$sql="DELETE FROM `cache` WHERE `timestamp` < '$tiempo';";
	mysqli_query($db_mysql,$sql);
}

function guardar_en_cache($key_cache,$tabla){
	global $db_mysql;
	$timestamp = time();
	$key_cache = str_replace("'","\'",$key_cache);
	$tabla = str_replace("'","\'",$tabla);
	$sql="INSERT INTO `cache` SET `clave` = '$key_cache', `html` = '$tabla', `timestamp` = '$timestamp';";
	if(mysqli_query($db_mysql,$sql)){
		return mysql_insert_id();
	}else{
		return false;
	}
}

function ajuste_inflacion_por_meses(){
	global $db_mysql;
	$inflacion = array();
	$sql="SELECT `inflacion`, `mes`, `ano` FROM `inflacion_2` LIMIT 999;";
	$result=mysqli_query($db_mysql,$sql);
	while( $dat=mysqli_fetch_array($result) ){
		$k = $dat['ano'] . '-' . $dat['mes'];
		$inflacion[$k] = $dat['inflacion'];
	}
	return $inflacion;
}

function ajuste_inflacion_por_mes($mes){
	global $db_mysql;
	$inflacion = 1;
	$array_trimestre = explode('-',$mes);
	$ano = $array_trimestre[0];
	$mes = $array_trimestre[1];
	$sql="SELECT `inflacion` FROM `inflacion_2` WHERE `ano` = '$ano' AND `mes` = '$mes' LIMIT 999;";
	$result=mysqli_query($db_mysql,$sql);
	if( $dat=mysqli_fetch_array($result) ){
		return $dat['inflacion'];
	}
	return 1;
}

function ajuste_inflacion_por_trimestre($trimestre){
	global $db_mysql;
	$inflacion = 1;
	$array_trimestre = explode('-',$trimestre);
	$ano = $array_trimestre[0];
	$q = $array_trimestre[1];
	$meses_trimestre = meses_trimestre($trimestre);
	$mes_1 = $meses_trimestre[0];
	$mes_2 = $meses_trimestre[1];
	$mes_3 = $meses_trimestre[2];
	$sql="SELECT `inflacion` FROM `inflacion_2` WHERE `ano` = '$ano' AND (`mes` = '$mes_1' OR `mes` = '$mes_2' OR `mes` = '$mes_1') LIMIT 999;";
	$result=mysqli_query($db_mysql,$sql);
	while( $dat=mysqli_fetch_array($result) ){
		$inflacion = $inflacion * $dat['inflacion'];
	}
	return $inflacion;
}

function ajuste_inflacion_por_ano($ano){
	global $db_mysql;
	$inflacion = 1;
	$sql="SELECT `inflacion` FROM `inflacion_2` WHERE `ano` = '$ano' LIMIT 999;";
	$result=mysqli_query($db_mysql,$sql);
	while( $dat=mysqli_fetch_array($result) ){
		$inflacion = $inflacion * $dat['inflacion'];
	}
	return $inflacion;
}

function stock($fecha){
	global $db_mysql;
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT SUM(`monto_total`) as `monto` FROM `stock` WHERE `ano` = '$ano' AND `mes` = '$mes' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		return round($dat['monto']);
	}
	return 0;
}

function caja($fecha){
	global $db_mysql;
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT `monto` FROM `saldos finales` WHERE `ano` = '$ano' AND `mes` = '$mes' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		return round($dat['monto']);
	}
	return 0;
}

function deudas_clientes($fecha){
	global $db_mysql;
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT SUM(`deuda`) as `deuda` FROM `deudas_clientes` WHERE `ano` = '$ano' AND `mes` = '$mes';";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		return round($dat['deuda']);
	}
	return 0;
}

function deudas_proveedores($fecha){
	global $db_mysql;
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT SUM(`deuda`) as `deuda` FROM `deudas_proveedores` WHERE `ano` = '$ano' AND `mes` = '$mes';";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		return round($dat['deuda']);
	}
	return 0;
}


function alarma_deuda($limite_deuda, $limite_dias){
	global $db_mysql;
	$limite_deuda = floatval($limite_deuda);
	$limite_dias = floatval($limite_dias);
	$mes=date('n');
	$ano=date('Y');
/*
	$sql = "SELECT
	`clientes`.`nombre_cliente`,
	`deudas_clientes`.`codigo_vendedor`,
	SUM(`deudas_clientes`.`deuda`) AS `deuda_excedida`
	FROM `deudas_clientes`
	LEFT JOIN `clientes` ON `clientes`.`id_cliente` = `deudas_clientes`.`id_cliente`
	WHERE `deudas_clientes`.`tipo_comprobante` != 'SIV' AND `deudas_clientes`.`dias_deuda` >= '$limite_dias'
	GROUP BY `clientes`.`nombre_cliente`
	ORDER BY `deuda_excedida` DESC LIMIT 100000";
*/

	$sql= "SELECT
	`id_cliente`,
	`nombre_cliente`,
	`codigo_vendedor`,
	`deuda`,
	`dias_deuda`
	FROM (
	SELECT
	`deudas_clientes`.`id_cliente` as `id_cliente`,
	`clientes`.`nombre_cliente` as `nombre_cliente`,
	`deudas_clientes`.`codigo_vendedor` as `codigo_vendedor`,
	SUM(`deudas_clientes`.`deuda`) as `deuda`,
	AVG(`deudas_clientes`.`dias_deuda`) as `dias_deuda`
	FROM `deudas_clientes`
	LEFT JOIN `clientes` ON `clientes`.`id_cliente` = `deudas_clientes`.`id_cliente`
	WHERE
	`deudas_clientes`.`tipo_comprobante` != 'SIV' AND
	`deudas_clientes`.`dias_deuda` > '$limite_dias' AND
	`deudas_clientes`.`mes` = '$mes' AND
	`deudas_clientes`.`ano` = '$ano'
	GROUP BY
	`deudas_clientes`.`id_cliente`) as `deudas_cien_dias`
	WHERE `deuda` > '$limite_deuda' 
	ORDER BY
	`deudas_cien_dias`.`deuda` DESC,
	`deudas_cien_dias`.`dias_deuda` DESC
	LIMIT 1;";

	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		return true;
	}
	return false;
}
















?>
