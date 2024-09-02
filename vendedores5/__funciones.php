<?php

function validador($expresion,$string){
	$expresion = '/' . $expresion . '/';
	if( preg_match( "$expresion" , $string ) ){
		return true;
	}else{
		return false;
	}
}
function hay_comillas($string){
	return validador( '(\')|(\")' , $string );
}
function mes_anterior($fecha){
	$time=strtotime($fecha);
	$nuevafecha=strtotime('-1 month',$time);
	return date('Y-n',$nuevafecha);
}
function ano_anterior($fecha){
	$int_fecha=intval($fecha);
	$int_fecha--;
	return $int_fecha;
}
function resta_meses($fecha,$meses_a_restar){
	$meses_a_restar=intval($meses_a_restar);
	$time=strtotime($fecha);
	$nuevafecha=strtotime('-' . $meses_a_restar . ' month',$time);
	return date('Y-n',$nuevafecha);
}
function resta_anos($fecha,$anos_a_restar){
	$int_anos_a_restar=intval($anos_a_restar);
	$int_fecha=intval($fecha);
	return $int_fecha - $int_anos_a_restar;
}
function reemplazar_punto($texto){
	return str_replace('.','&#46;',$texto);
}
function devolver_punto($texto){
	return str_replace('&#46;','.',$texto);
}
function es_par($numero){
	if($numero%2==0){
		return true;
	}else{
		return false;
	}
}
function redondear($x){
	return round($x,3);
}
function numero_legible( $valor ){
	$valor = number_format( $valor , 0 , ',', '.');
	return $valor;
}
function numero_limpio( $valor ){
	$valor = number_format( $valor , 0 , '', '');
	return $valor;
}
function rotar_matriz( $array ){
	$out = array();
	foreach($array as $key => $subarr){
		if(is_array($subarr)){
			foreach($subarr as $subkey => $subvalue){
				$out[$subkey][$key] = $subvalue;
			}
		}else{
			$out[$subkey][$key] = $subvalue;
		}
	}
	return $out;
}

function promedio($array_datos,$cantidad){
	if( is_numeric($cantidad) && $cantidad != 0 ){
		return array_sum($array_datos) / $cantidad;
	}
	return false;
}

function no_nulo($array_datos){
	if( is_array($array_datos) && count($array_datos) > 0 ){
		foreach($array_datos as $valor){
			if( $valor != 0 ){
				return true;
			}
		}
	}
	return false;
}

function numero_legible_redondeado( $valor ){
	$valor = floatval($valor);
	$valor = number_format( $valor , 0 , ',', '.');
	return $valor;
}

function numero_en_miles( $valor ){
	$valor = floatval($valor) / 1000;
	$valor = number_format( $valor , 0 , ',', '.');
	return $valor;
}

function numero_legible_centecimales( $valor ){
	$valor = floatval($valor);
	$valor = number_format( $valor , 2 , ',', '.');
	return $valor;
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

function clientes_activos(){
	global $db_mysql;
	$sql="SELECT count(`id_cliente`) as `activos` FROM `clientes` WHERE `cliente_activo_con_excel` = 1;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		return intval($dat['activos']);
	}
	return 0;
}

function get_clientes_x_vendedor($codigo_vendedor){
	global $db_mysql;
	$sql="SELECT DISTINCT(`clientes`.`id_cliente`), `clientes`.`nombre_cliente`, `clientes`.`bonificacion` 
	FROM `ventas` LEFT JOIN `clientes` ON `ventas`.`id_cliente` = `clientes`.`id_cliente` 
	LEFT JOIN `vendedores` ON `vendedores`.`codigo_vendedor` = `clientes`.`codigo_vendedor` 
	WHERE `vendedores`.`codigo_vendedor` = '$codigo_vendedor' AND `ventas`.`anulada` = '0' AND `clientes`.`es_excel` = '0' 
	GROUP BY `clientes`.`id_cliente` 
	ORDER BY `clientes`.`nombre_cliente` ASC;";
	$clientes = array();
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$k = strval($dat['id_cliente']);
		$clientes[$k] = $dat;
	}
	return $clientes;
}

function get_clientes_x_vendedor_zona($codigo_vendedor, $id_zona){
	global $db_mysql;
	if( $id_zona == 'Selecciona la zona' ){
		return get_clientes_x_vendedor($codigo_vendedor);
	}

	$sql="SELECT DISTINCT(`clientes`.`id_cliente`), `clientes`.`nombre_cliente`, `clientes`.`bonificacion` 
	FROM `ventas` 
	LEFT JOIN `clientes` ON `ventas`.`id_cliente` = `clientes`.`id_cliente` 
	LEFT JOIN `vendedores` ON `vendedores`.`codigo_vendedor` = `clientes`.`codigo_vendedor` 
	WHERE `vendedores`.`codigo_vendedor` = '$codigo_vendedor' 
	AND `clientes`.`id_zona` = '$id_zona' 
	AND `ventas`.`anulada` = '0' 
	GROUP BY `clientes`.`id_cliente` ORDER BY `clientes`.`nombre_cliente` ASC;";
	$clientes = array();
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$k = strval($dat['id_cliente']);
		$clientes[$k] = $dat;
	}
	return $clientes;
}

function vendedores_zonas(){
	global $db_mysql;
	$vendedores = array();
	$todas = array();

	$sql="SELECT `vendedores`.`codigo_vendedor`, `zonas`.`id_zona`, `zonas`.`nombre_zona`
	FROM `clientes` 
	LEFT JOIN `zonas` ON `zonas`.`id_zona` = `clientes`.`id_zona` 
	LEFT JOIN `vendedores` ON `vendedores`.`codigo_vendedor` = `clientes`.`codigo_vendedor` 
	ORDER BY `vendedores`.`codigo_vendedor` ASC, `zonas`.`nombre_zona` ASC;";

	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$codigo_vendedor = strval($dat['codigo_vendedor']);
		$zona = $dat['id_zona'];
		$vendedores[$codigo_vendedor][$zona] = $dat['nombre_zona'];
		$todas[$zona] = $dat['nombre_zona'];
	}
	$vendedores['TODAS'] = $todas;
	return $vendedores;
}

function get_cliente( $id_cliente){
	global $db_mysql;
	$sql="SELECT * FROM `clientes` WHERE `id_cliente` = '$id_cliente' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		return $dat;
	}
	return false;
}

function get_vendedores(){
	global $db_mysql;
	$vendedores = array();
	$sql="SELECT `codigo_vendedor`, `nombre_vendedor` FROM `vendedores` WHERE `es_vendedor` = '1' ORDER BY `nombre_vendedor` ASC LIMIT 999;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$codigo_vendedor = strval($dat['codigo_vendedor']);
		$vendedores[$codigo_vendedor] = strval($dat['nombre_vendedor']);
	}
	return $vendedores;
}

function get_zonas(){
	global $db_mysql;
	$zonas = array();
	$sql="SELECT `id_zona`, `nombre_zona` FROM `zonas` ORDER BY `nombre_zona` ASC LIMIT 999;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$k = strval($dat['id_zona']);
		$zonas[$k] = strval($dat['nombre_zona']);
	}
	return $zonas;
}

function get_marcas(){
	global $db_mysql;
	$marcas = array();
	$sql="SELECT `id_marca`, `nombre_marca` FROM `marcas` LIMIT 99999;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$id_marca = strval($dat['id_marca']);
		$marcas[$id_marca] = strval($dat['nombre_marca']);
	}
	return $marcas;
}

function get_super_rubros(){
	global $db_mysql;
	$super_rubros = array();
	$sql="SELECT `id_super_rubro`, `nombre_super_rubro`, `orden` FROM `super rubros` LIMIT 99999;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$id_super_rubro = strval($dat['id_super_rubro']);
		$super_rubros[$id_super_rubro]['nombre'] = strval($dat['nombre_super_rubro']);
		$super_rubros[$id_super_rubro]['orden'] = $dat['orden'];
	}
	return $super_rubros;
}

function get_super_rubros_especiales(){
	global $db_mysql;
	$super_rubros = array();
	$sql="SELECT `id_super_rubro`, `nombre_super_rubro`, `orden` FROM `super rubros` WHERE `orden` <= 25 LIMIT 25;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$id_super_rubro = strval($dat['id_super_rubro']);
		$super_rubros[$id_super_rubro]['nombre'] = strval($dat['nombre_super_rubro']);
		$super_rubros[$id_super_rubro]['orden'] = $dat['orden'];
	}
	return $super_rubros;
}

function get_vendedores_activos(){
	global $db_mysql;
	$vendedores = array();
	$sql="SELECT `codigo_vendedor`, `nombre_vendedor` FROM `vendedores` WHERE `activo` = '1' AND `es_vendedor` = '1' LIMIT 999;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$codigo_vendedor = strval($dat['codigo_vendedor']);
		$vendedores[$codigo_vendedor] = strval($dat['nombre_vendedor']);
	}
	return $vendedores;
}

function promedio_ventas( $id_cliente, $meses ){
	if( is_int($meses) && $meses > 0 ){
		global $db_mysql;
		$monto = 0;
		$unidades = 0;
		$condiciones_fecha = sql_meses($meses);
		
		$ids_asociados = ids_clientes_asociados( $id_cliente );
		$query_ids_cliente = query_ids_cliente($ids_asociados);
		
		$sql="SELECT SUM(`monto_total`) as `monto`, SUM(`cantidad`) as `unidades` FROM `ventas` 
		WHERE $condiciones_fecha AND $query_ids_cliente AND `anulada` = '0';";
	//	echo "<p>$sql</p>";
		$result=mysqli_query($db_mysql,$sql);
		while($dat=mysqli_fetch_array($result)){
			$monto = floatval($dat['monto']/ $meses);
			$unidades = floatval($dat['unidades']/ $meses);
		}
		return array('monto'=>$monto,'unidades'=>$unidades);
	}
	return false;
}

///////////////////////////////

function ventas_mes( $id_cliente, int $mes, int $ano ){
	global $db_mysql;
	$monto = 0;
	$unidades = 0;

	$ids_asociados = ids_clientes_asociados( $id_cliente );
	$query_ids_cliente = query_ids_cliente($ids_asociados);

	$sql="SELECT SUM(`monto_total`) as `monto`, SUM(`cantidad`) as `unidades` FROM `ventas` 
	WHERE `ano` = '$ano' AND `mes` = '$mes' AND $query_ids_cliente AND `anulada` = '0';";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$monto = floatval($dat['monto']);
		$unidades = floatval($dat['unidades']);
	}
	return array('monto'=>$monto,'unidades'=>$unidades);
}

function ventas_mes_corriente( $id_cliente ){
	return ventas_mes( $id_cliente, date('m'), date('Y') );
}

function ventas_mes_anterior( $id_cliente ){
	$ano = intval(date('Y'));
	$mes = intval(date('m'));
	$mes--;
	if($mes == 0 ){
		$mes = 12;
		$ano--;
	}
	return ventas_mes( $id_cliente, $mes, $ano );
}

/*
function ventas_ultimo_mes( $id_cliente ){
	global $db_mysql;
	$monto = 0;
	$unidades = 0;
	$mes = date('n');
	$ano = date('Y');
	$porcentaje_periodo = date("d") / date("d",mktime(0,0,0,date('n')+1,0,date('Y')));
	$sql="SELECT SUM(`monto_total`) as `monto`, SUM(`cantidad`) as `unidades` FROM `ventas` 
	WHERE `ano` = '$ano' AND `mes` = '$mes' AND `id_cliente` = '$id_cliente' AND `anulada` = '0';";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		if($porcentaje_periodo==0){
			$porcentaje_periodo = 0.033;
		}
		$monto = floatval($dat['monto']) / $porcentaje_periodo;
		$unidades = round($dat['unidades'] / $porcentaje_periodo);
	}
	return array('monto'=>$monto,'unidades'=>$unidades);
}

function ventas_ultimo_mes_x_cliente_x_marca( $id_cliente, $id_marca ){
	global $db_mysql;
	$monto = 0;
	$unidades = 0;
	$sql="SELECT SUM(`monto_total`) as `monto`, SUM(`cantidad`) as `unidades` FROM `ventas` WHERE UNIX_TIMESTAMP(`fecha`) > (UNIX_TIMESTAMP(NOW())-2592000) AND `id_cliente` = '$id_cliente' AND `id_marca` = '$id_marca' AND `anulada` = '0';";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$monto = floatval($dat['monto']);
		$unidades = floatval($dat['unidades']);
	}
	return array('monto'=>$monto,'unidades'=>$unidades);
}
*/

function sql_clientes($clientes){
	if( is_array($clientes) && count($clientes) ){
		$array_sql = array();
		foreach( $clientes as $id_cliente ){
			$array_sql[] = "`ventas`.`id_cliente` = '$id_cliente'";
		}
		return '(' . implode(' OR ', $array_sql ) . ')';
	}
	return false;
}

function sql_meses($meses){
	if( is_int($meses) ){
		$fecha_inicio = date("Y-n-j", strtotime('-' . $meses . ' month', strtotime('first day of previous month')));
		$fecha_final = date("Y-n-j", strtotime("last day of previous month"));
		return " `fecha` BETWEEN '$fecha_inicio' AND '$fecha_final' ";
	}
	return false;
}

function ventas_x_clientes( $clientes, $meses ){
	global $db_mysql;
	$keys_clientes = array_keys($clientes);

	if( $meses ){
		$condiciones_fecha = sql_meses($meses);
	}else{
		$ano_actual = date('Y');
		$mes_actual = date('n');
		$condiciones_fecha =  " `ano` = '$ano_actual' AND `mes` = '$mes_actual' ";
	}
	$ventas = array();

	foreach( $keys_clientes as $id_cliente ){
		$id_cliente = strval($id_cliente);
		$ids_asociados = ids_clientes_asociados($id_cliente);
		$query_ids_cliente = query_ids_cliente($ids_asociados);

		$sql="SELECT SUM(`monto_total`) as `monto`, SUM(`cantidad`) as `unidades` FROM `ventas` 
		WHERE $condiciones_fecha AND $query_ids_cliente AND `anulada` = '0';";
		$result=mysqli_query($db_mysql,$sql);
	//	echo "<p>$sql</p>";
		if($dat=mysqli_fetch_array($result)){
			$ventas[$id_cliente]['unidades'] = $dat['unidades'];
			$ventas[$id_cliente]['monto'] = $dat['monto'];
		}
	}

	return $ventas;
}

function ventas_x_cliente_x_marcas( $id_cliente, $meses ){
	global $db_mysql;
	if( $meses ){
		$condiciones_fecha = sql_meses($meses);
	}else{
		$ano_actual = date('Y');
		$mes_actual = date('n');
		$condiciones_fecha =  " `ano` = '$ano_actual' AND `mes` = '$mes_actual' ";
	}
	$ventas = array();
	$sql="SELECT `marcas`.`id_marca` as `id_marca`, SUM(`ventas`.`cantidad`) as `unidades`, SUM(`ventas`.`monto_total`) as `monto`
	FROM `ventas` LEFT JOIN `marcas` ON `marcas`.`id_marca` = `ventas`.`id_marca`
	WHERE `ventas`.`id_cliente` = '$id_cliente' AND $condiciones_fecha AND `ventas`.`anulada` = '0'
	GROUP BY `id_marca`;";
	$result=mysqli_query($db_mysql,$sql);
//	echo "<p>$sql</p>";
	while($dat=mysqli_fetch_array($result)){
		$id_marca = strval($dat['id_marca']);
		$ventas[$id_marca]['unidades'] = $dat['unidades'];
		$ventas[$id_marca]['monto'] = $dat['monto'];
	}
	return $ventas;
}

function ventas_x_cliente_x_super_rubros( $id_cliente, $meses ){
	global $db_mysql;
	if( $meses === 1 ){
		$ano = intval(date('Y'));
		$mes = intval(date('m'));
		$mes--;
		if($mes == 0 ){
			$mes = 12;
			$ano--;
		}
		$condiciones_fecha =  " `ano` = '$ano' AND `mes` = '$mes' ";
	}elseif( $meses ){
		$condiciones_fecha = sql_meses($meses);
	}else{
		$ano_actual = date('Y');
		$mes_actual = date('n');
		$condiciones_fecha =  " `ano` = '$ano_actual' AND `mes` = '$mes_actual' ";
	}

	$ids_asociados = ids_clientes_asociados($id_cliente);
	$query_ids_cliente = query_ids_cliente($ids_asociados);

	$ventas = array();
	$sql="SELECT `super rubros`.`id_super_rubro` as `id_super_rubro`, SUM(`ventas`.`cantidad`) as `unidades`, SUM(`ventas`.`monto_total`) as `monto`
	FROM `ventas`
	LEFT JOIN `rubros` ON `rubros`.`id_rubro` = `ventas`.`id_rubro`
	LEFT JOIN `super rubros` ON `super rubros`.`id_super_rubro` = `rubros`.`id_super_rubro`
	WHERE $query_ids_cliente AND $condiciones_fecha AND `ventas`.`anulada` = '0'
	GROUP BY `id_super_rubro`;";
	$result=mysqli_query($db_mysql,$sql);
	// echo "<!-- $sql -->";
	while($dat=mysqli_fetch_array($result)){
		$id_super_rubro = strval($dat['id_super_rubro']);
		$ventas[$id_super_rubro]['unidades'] = $dat['unidades'];
		$ventas[$id_super_rubro]['monto'] = $dat['monto'];
	}
	return $ventas;
}

function get_ventas_clientes_sr_marcas() : array{
	global $db_mysql;
	$ventas = [];
	$sql="SELECT 
	`ventas`.`id_cliente`, `articulos`.`id_marca`, `rubros`.`id_super_rubro`,
	SUM(`ventas`.`cantidad`) as `unidades`
	FROM `ventas` 
	LEFT JOIN `articulos` ON `articulos`.`id_articulo` = `ventas`.`id_articulo` 
	LEFT JOIN `rubros` ON `rubros`.`id_rubro` = `articulos`.`id_rubro` 
	WHERE `ventas`.`anulada` = 0
	AND `ventas`.`ano` = YEAR(NOW())
	AND `ventas`.`mes` = MONTH(NOW())
	AND `articulos`.`id_marca` != 'NULL' 
	AND `rubros`.`id_super_rubro` != 'NULL' 
	GROUP BY `ventas`.`id_cliente`, `articulos`.`id_marca`, `rubros`.`id_super_rubro`
	LIMIT 9999;";
	$result=mysqli_query($db_mysql,$sql);
	// echo "<!-- $sql -->";
	while(@$dat=mysqli_fetch_array($result)){
		if( $dat['unidades'] > 0 ){
			$id_cliente = strval($dat['id_cliente']);
			$id_marca = strval($dat['id_marca']);
			$id_super_rubro = strval($dat['id_super_rubro']);
			$ventas[$id_cliente][$id_super_rubro][$id_marca] = $dat['unidades'];
		}
	}
	return $ventas;
}

function get_descuentos_clientes_marcas() : array{
	global $db_mysql;
	$descuentos = [];
	$sql="SELECT `codigo_cliente`, `codigo_marca`, `porcentaje_descuento` FROM `descuentos_clientes_marcas` LIMIT 99999;";
	$result=mysqli_query($db_mysql,$sql);
	// echo "<!-- $sql -->";
	while(@$dat=mysqli_fetch_array($result)){
		$codigo_cliente = strval($dat['codigo_cliente']);
		$codigo_marca = strval($dat['codigo_marca']);
		$descuentos[$codigo_cliente][$codigo_marca] = $dat['porcentaje_descuento'];
	}
	return $descuentos;
}

function descuento_maximo_marca($descuentos_clientes_marcas, $ventas_clientes_sr_marcas, $id_cliente, $id_super_rubro){

	if( !isset($ventas_clientes_sr_marcas[$id_cliente][$id_super_rubro]) ){
		// print_r($ventas_clientes_sr_marcas[$id_cliente][$id_super_rubro]);
		return 0;
	}

	$marcas = array_keys($ventas_clientes_sr_marcas[$id_cliente][$id_super_rubro]);

	if( !count($marcas) ){
		// print_r($marcas);
		return 0;
	}

	$descuentos_marcas = [];
	foreach( $marcas as $codigo_marca ){
		if( isset($descuentos_clientes_marcas[$id_cliente][$codigo_marca]) ){
			$descuentos_marcas[$codigo_marca] = floatval($descuentos_clientes_marcas[$id_cliente][$codigo_marca]);
		}
	}

	if( count( $descuentos_marcas) ){
		arsort($descuentos_marcas);

		// echo "<pre>";
		// print_r($descuentos_marcas);
		// echo "</pre>";

		// echo "<p>-->" . array_values($descuentos_marcas)[0] . "</p>";

		return array_values($descuentos_marcas)[0];
	}

	return 0;
}

// function descuento_maximo_marca($id_cliente, $ventas_clientes_sr_marcas_todas){
// 	global $db_mysql;
// 	$descuentos = [];
// 	$sql="SELECT `rubros`.`id_super_rubro` as `id_super_rubro`, `articulos`.`id_marca` as `id_marca`, 
// 	MAX(`descuentos_clientes_marcas`.`porcentaje_descuento`) as `descuento` 
// 	FROM `rubros`
// 	LEFT JOIN `articulos` ON `articulos`.`id_rubro` = `rubros`.`id_rubro` 
// 	LEFT JOIN `descuentos_clientes_marcas` ON `articulos`.`id_marca` = `descuentos_clientes_marcas`.`codigo_marca` 
// 	WHERE `descuentos_clientes_marcas`.`codigo_cliente` = '$id_cliente' 
// 	GROUP BY `rubros`.`id_super_rubro`, `articulos`.`id_marca` LIMIT 999;";
// 	$result=mysqli_query($db_mysql,$sql);
// 	// echo "<!-- $sql -->";
// 	while($dat=mysqli_fetch_array($result)){
// 		$id_super_rubro = strval($dat['id_super_rubro']);
// 		$id_marca = strval($dat['id_marca']);

// 		if( isset( $ventas_clientes_sr_marcas_todas[$id_cliente][$id_super_rubro][$id_marca] ) ){
// 			$descuentos[$id_super_rubro] = $dat['descuento'];
// 			return $descuentos;
// 		}
// 	}
// 	return $descuentos;
// }

// function descuento_maximo_marca_backup($id_cliente){
// 	global $db_mysql;
// 	$descuentos = [];
// 	$sql="SELECT `rubros`.`id_super_rubro` as `id_super_rubro`, 
// 	MAX(`descuentos_clientes_marcas`.`porcentaje_descuento`) as `descuento` 
// 	FROM `rubros`
// 	LEFT JOIN `articulos` ON `articulos`.`id_rubro` = `rubros`.`id_rubro` 
// 	LEFT JOIN `descuentos_clientes_marcas` ON `articulos`.`id_marca` = `descuentos_clientes_marcas`.`codigo_marca` 
// 	WHERE `descuentos_clientes_marcas`.`codigo_cliente` = '$id_cliente' 
// 	GROUP BY `rubros`.`id_super_rubro` LIMIT 9999;";
// 	$result=mysqli_query($db_mysql,$sql);
// 	// echo "<!-- $sql -->";
// 	while($dat=mysqli_fetch_array($result)){
// 		$id_super_rubro = strval($dat['id_super_rubro']);
// 		$descuentos[$id_super_rubro] = $dat['descuento'];
// 	}
// 	return $descuentos;
// }

function descuento_detalles_marcas($id_cliente, $id_super_rubro){
	global $db_mysql;
	$marcas = [];
	$sql="SELECT DISTINCT(`descuentos_clientes_marcas`.`codigo_marca`) as `codigo_marca`, 
	`marcas`.`nombre_marca` as `nombre_marca`, 
	`descuentos_clientes_marcas`.`porcentaje_descuento` as `descuento` 
	FROM `rubros` 
	LEFT JOIN `articulos` ON `articulos`.`id_rubro` = `rubros`.`id_rubro` 
	LEFT JOIN `descuentos_clientes_marcas` ON `articulos`.`id_marca` = `descuentos_clientes_marcas`.`codigo_marca` 
	LEFT JOIN `marcas` ON `articulos`.`id_marca` = `marcas`.`id_marca` 
	WHERE `descuentos_clientes_marcas`.`codigo_cliente` = '$id_cliente' 
	AND `rubros`.`id_super_rubro` = '$id_super_rubro' 
	ORDER BY `descuento` DESC
	LIMIT 9999;";
	$result=mysqli_query($db_mysql,$sql);
	// echo "<!-- $sql -->";
	while(@$dat=mysqli_fetch_array($result)){
		$codigo_marca = strval($dat['codigo_marca']);
		$marcas[$codigo_marca]['nombre_marca'] = $dat['nombre_marca'];
		$marcas[$codigo_marca]['descuento'] = $dat['descuento'];
	}
	return $marcas;
}

function ventas_sr_cliente_marcas( string $id_super_rubro, string $id_cliente, string $marcas) : array{
	global $db_mysql;
	$ventas = [];
	$sql="SELECT `articulos`.`id_marca`,
	SUM(`ventas`.`cantidad`) as `unidades`, 
	SUM(`ventas`.`monto_total`) as `monto` 
	FROM `ventas` 
	LEFT JOIN `articulos` ON `articulos`.`id_articulo` = `ventas`.`id_articulo` 
	LEFT JOIN `rubros` ON `rubros`.`id_rubro` = `articulos`.`id_rubro` 
	WHERE `ventas`.`id_cliente` = '$id_cliente' 
	AND `rubros`.`id_super_rubro` = '$id_super_rubro' 
	AND `articulos`.`id_marca` IN ($marcas)
	AND `ventas`.`ano` = YEAR(NOW())
	AND `ventas`.`mes` = MONTH(NOW())
    GROUP BY `articulos`.`id_marca`
	LIMIT 9999;";
	$result=mysqli_query($db_mysql,$sql);
	// echo "<!-- $sql -->";
	while(@$dat=mysqli_fetch_array($result)){
		$codigo_marca = strval($dat['id_marca']);
		$ventas[$codigo_marca]['unidades'] = $dat['unidades'];
		$ventas[$codigo_marca]['monto'] = $dat['monto'];
	}
	return $ventas;
}

function color_promedio($valor, $promedio, $tipo){
	if( $valor >= ($promedio * 1.1) ){
		$class_color = 'color_bueno';
	}elseif( $valor < ($promedio * 1.1) && $valor >= ($promedio * 0.9) ){
		$class_color = 'color_regular';
	}elseif( $valor < ($promedio * 0.9) && $valor >= ($promedio * 0.7) ){
		$class_color = 'color_malo';
	}elseif( $valor < ($promedio * 0.7) && $valor >= ($promedio * 0.2) ){
		$class_color = 'color_mm';
	}elseif( $valor < ($promedio * 0.2) ){
		$class_color = 'color_mmm';
	}
	if( $valor <= 0 ){
		$class_color = 'color_mmm';
	}
	if($tipo!='unidades'){
		$tipo = 'monto';
	}
	return $class_color . '_' . $tipo;
	return $class_color;
	//cuando el valor es un 10% superior al promedio el color es verde
	//cuando el valor está entre el +10% y -10% del promedio el color es verde
	//cuando el valor está entre el -10% y -30% del promedio el color es amarillo anaranjado
	//cuando el valor es mas del 30% inferior al promedio el color es rojo
}

function get_config($k){
	global $db_mysql;
	$sql="SELECT `v` FROM `variables` WHERE `k` = '$k' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		return $dat['v'];
	}
	return false;
}

function get_clientes_activos_x_vendedor($codigo_vendedor){
	global $db_mysql;
//	$sql="SELECT count(`id_cliente`) FROM `clientes` WHERE `activo` = 1;";
	$sql="SELECT DISTINCT(`clientes`.`id_cliente`), `clientes`.`nombre_cliente`, `clientes`.`bonificacion` 
	FROM `ventas` 
	LEFT JOIN `clientes` ON `ventas`.`id_cliente` = `clientes`.`id_cliente` 
	LEFT JOIN `vendedores` ON `vendedores`.`codigo_vendedor` = `clientes`.`codigo_vendedor` 
	WHERE `clientes`.`cliente_activo_con_excel` = 1 AND 
	`vendedores`.`codigo_vendedor` = '$codigo_vendedor' AND 
	`ventas`.`anulada` = '0' AND
	`clientes`.`es_excel` = '0' 
	GROUP BY `clientes`.`id_cliente` 
	ORDER BY `clientes`.`nombre_cliente` ASC;";
	$clientes = array();
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$k = strval($dat['id_cliente']);
		$clientes[$k] = $dat;
	}
	return $clientes;
}

function get_clientes_activos_x_vendedor_zona($codigo_vendedor, $id_zona){
	global $db_mysql;
	if( $id_zona == 'Selecciona la zona' ){
		return get_clientes_activos_x_vendedor($codigo_vendedor);
	}
//	$sql="SELECT count(`id_cliente`) FROM `clientes` WHERE `activo` = 1;";
	$sql="SELECT DISTINCT(`clientes`.`id_cliente`), `clientes`.`nombre_cliente`, `clientes`.`bonificacion` 
	FROM `ventas` 
	LEFT JOIN `clientes` ON `ventas`.`id_cliente` = `clientes`.`id_cliente` 
	LEFT JOIN `vendedores` ON `vendedores`.`codigo_vendedor` = `clientes`.`codigo_vendedor` 
	WHERE `clientes`.`activo_taco` = 1 
	AND `vendedores`.`codigo_vendedor` = '$codigo_vendedor'
	AND `clientes`.`id_zona` = '$id_zona' 
	AND `ventas`.`anulada` = '0' 
	GROUP BY `clientes`.`id_cliente` 
	ORDER BY `clientes`.`nombre_cliente` ASC;";//3 meses hacia atrás desde ahora
	$clientes = array();
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$k = strval($dat['id_cliente']);
		$clientes[$k] = $dat;
	}
	return $clientes;
}

function saldo_inicial_distrisuper( $id_cliente ){
	global $db_mysql;
	$sql="SELECT * FROM `saldos_operaciones_distrisuper_3` WHERE `id_cliente` = '$id_cliente' ORDER BY `id_saldo` DESC LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		return $dat;
	}
	return false;
}

function saldo_inicial_fric_rot( $id_cliente ){
	global $db_mysql;
	$sql="SELECT * FROM `saldos_operaciones_fric_rot_3` WHERE `id_cliente` = '$id_cliente' ORDER BY `id_saldo` DESC LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		return $dat;
	}
	return false;
}

function tiene_op_esp( $id_cliente ){

	$saldo_fric = saldo_inicial_fric_rot( $id_cliente );
	if( $saldo_fric && $saldo_fric['saldo'] != 0 ){
		return true;
	}

	$saldo_distri = saldo_inicial_distrisuper( $id_cliente );
	if( $saldo_distri && $saldo_distri['saldo'] != 0 ){
		return true;
	}

	return false;
}

function get_por_que_no_compra($id_cliente, $id_super_rubro){
	global $db_mysql;
	$id_cliente = trim( $id_cliente );
	$id_super_rubro = trim( $id_super_rubro );
	$sql="SELECT `por_que_no_compra` FROM `por_que_no_compra` WHERE `id_cliente` = '$id_cliente' AND `id_super_rubro` = '$id_super_rubro' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		return $dat['por_que_no_compra'];
	}
	return '';
}

function update_por_que_no_compra( $id_cliente, $id_super_rubro, $por_que_no_compra ){
	global $db_mysql;
	$id_cliente = trim( $id_cliente );
	$id_super_rubro = trim( $id_super_rubro );
	$por_que_no_compra = trim( $por_que_no_compra );
	$sql="DELETE FROM `por_que_no_compra` WHERE `id_cliente` = '$id_cliente' AND `id_super_rubro` = '$id_super_rubro';";
	if( mysqli_query($db_mysql,$sql) ){
		if( strlen($por_que_no_compra) > 0 ){
			$sql="INSERT INTO `por_que_no_compra` SET `por_que_no_compra` = '$por_que_no_compra', `id_cliente` = '$id_cliente', `id_super_rubro` = '$id_super_rubro';";
			if( mysqli_query($db_mysql,$sql) ){
				return true;
			}
		}else{
			return true;
		}
	}
	return false;	
}


function ids_clientes_asociados($id_cliente){
	global $db_mysql;
	$ids = [$id_cliente];
	$sql="SELECT `clientes`.`id_cliente` FROM `asociaciones_clientes`
	LEFT JOIN `clientes`
	ON (
		`asociaciones_clientes`. `cuenta_op_fric_rot` = `clientes`.`codigo_particular` OR 
		`asociaciones_clientes`. `cuenta_op_distrisuper` = `clientes`.`codigo_particular` 
	)
	WHERE `asociaciones_clientes`.`codigo` = '$id_cliente' LIMIT 99;";
	//echo "<p>$sql</p>";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$ids[] = $dat['id_cliente'];
	}
	mysqli_free_result($result);
	return $ids;
}

function query_ids_cliente($ids_asociados){
	$condicion_ids = [];
	foreach($ids_asociados as $id){
		$condicion_ids[] = "`ventas`.`id_cliente` = '" . $id . "'";
	}
	$query_ids = implode(' OR ', $condicion_ids);
	return '(' . $query_ids . ')';
}

// function cliente_activo_con_excel( $id_cliente ){
// 	global $db_mysql;
// 	$ids_asociados = ids_clientes_asociados( $id_cliente );
// 	$query_ids_cliente = query_ids_cliente($ids_asociados);

// 	$sql="SELECT sum(`monto_total`) as `total` FROM `ventas` 
// 	WHERE `ano` = YEAR(NOW()) AND `mes` = MONTH(NOW()) 
// 	AND $query_ids_cliente AND `anulada` = '0';";
// 	$result=mysqli_query($db_mysql,$sql);
// 	if($dat=mysqli_fetch_array($result)){
// 		@mysqli_free_result($result);
// 		if( $dat['total'] >= get_config('monto_cliente_activo') ){
// 			return 1;
// 		}
// 	}
// 	return 0;
// }
?>