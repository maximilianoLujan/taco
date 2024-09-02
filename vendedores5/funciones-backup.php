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



function fechas_a_mostrar($unidad_tiempo,$comienzo_tiempo,$cantidad_tiempo){
	$comienzo_tiempo = intval($comienzo_tiempo);
	$cantidad_tiempo = intval($cantidad_tiempo);
	if($unidad_tiempo=='anos'){
		$fecha_actual=date('Y');
		$fecha_comienzo=resta_anos($fecha_actual,$comienzo_tiempo);
		$anos_restantes=$cantidad_tiempo;
		$ano=$fecha_comienzo;
		while($anos_restantes>0){
			$anos[]=$ano;
			$ano=ano_anterior($ano);
			$anos_restantes--;
		}
		return $anos;
	}else{
		$fecha_actual=date('Y-n');
		$fecha_comienzo=resta_meses($fecha_actual,$comienzo_tiempo);
		$meses_restantes=$cantidad_tiempo;
		$mes=$fecha_comienzo;
		while($meses_restantes>0){
			$meses[]=$mes;
			$mes=mes_anterior($mes);
			$meses_restantes--;
		}
		return $meses;
	}
}

function fecha_legible($fecha){
	if(strlen($fecha)>4){
		if($f = explode('-',$fecha)){
			$fecha = $f[1] . '/' . $f[0];
		}
	}
	return $fecha;
}

/*
function promedio($array_datos,$cantidad){
	if( is_array($array_datos) && count($array_datos) > 0 ){
		$cantidad_elementos = count($array_datos);
		if( is_int($cantidad) && $cantidad > 0 && $cantidad_elementos > $cantidad){
			$offset = $cantidad_elementos - $cantidad - 1;// -1, es para que no tome en cuenta el mes actual
			$array_datos = array_slice( $array_datos, $offset, $cantidad );
			$cantidad_elementos = $cantidad;
		}
		$total = floatval(0);
		foreach($array_datos as $valor){
			$total = $total + $valor;
		}
		echo "cant. $cantidad_elementos";
		$promedio = $total/$cantidad_elementos;
		return $promedio;
	}else{
		return false;
	}
}*/

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
	$sql="SELECT count(`id_cliente`) as `activos` FROM `clientes` WHERE `activo_taco` = 1;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		return intval($dat['activos']);
	}
	return 0;
}

function get_clientes_x_vendedor($codigo_vendedor){
	global $db_mysql;
	$sql="SELECT DISTINCT(`clientes`.`id_cliente`), `clientes`.`nombre_cliente` FROM `ventas` LEFT JOIN `clientes` ON `ventas`.`id_cliente` = `clientes`.`id_cliente` LEFT JOIN `vendedores` ON `vendedores`.`codigo_vendedor` = `clientes`.`codigo_vendedor` WHERE `vendedores`.`codigo_vendedor` = '$codigo_vendedor' AND `ventas`.`anulada` = '0' GROUP BY `clientes`.`id_cliente` ORDER BY `clientes`.`nombre_cliente` ASC;";
	$clientes = array();
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$k = strval($dat['id_cliente']);
		$clientes[$k] = $dat;
	}
	return $clientes;
}


function get_clientes_activos_x_vendedor($codigo_vendedor){
	global $db_mysql;
//	$sql="SELECT count(`id_cliente`) FROM `clientes` WHERE `activo` = 1;";
	$sql="SELECT DISTINCT(`clientes`.`id_cliente`), `clientes`.`nombre_cliente` FROM `ventas` LEFT JOIN `clientes` ON `ventas`.`id_cliente` = `clientes`.`id_cliente` LEFT JOIN `vendedores` ON `vendedores`.`codigo_vendedor` = `clientes`.`codigo_vendedor` WHERE `clientes`.`activo_taco` = 1 AND `vendedores`.`codigo_vendedor` = '$codigo_vendedor' AND `ventas`.`anulada` = '0' GROUP BY `clientes`.`id_cliente` ORDER BY `clientes`.`nombre_cliente` ASC;";//3 meses hacia atrás desde ahora
	$clientes = array();
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$k = strval($dat['id_cliente']);
		$clientes[$k] = $dat;
	}
	return $clientes;
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



function numero_para_promedio($array_fechas){
	$numero_columnas = floatval(count($array_fechas));
	if( $numero_columnas>0 ){
		$ultima_importacion = ultima_importacion();
		if($ultima_importacion){
			$array_ultima_importacion = explode("-",$ultima_importacion);
			$ano = $array_ultima_importacion[0];
			$mes = $array_ultima_importacion[1];
			$dia = $array_ultima_importacion[2];
			$total_dias = date("d",mktime(0,0,0,$mes+1,0,$ano));
			$numero_columnas = ($numero_columnas-1)+($dia/$total_dias);
		}
	}
	return $numero_columnas;
}
/*
function promedio_ventas_12( $id_cliente ){
	global $db_mysql;
	$monto = 0;
	$unidades = 0;
	$sql="SELECT SUM(`monto_total`) as `monto`, SUM(`cantidad`) as `unidades` FROM `ventas` WHERE UNIX_TIMESTAMP(`fecha`) > (UNIX_TIMESTAMP(NOW())-31536000) AND `id_cliente` = '$id_cliente' AND `anulada` = '0';";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$monto = floatval($dat['monto']/12);
		$unidades = floatval($dat['unidades']/12);
	}
	return array('monto'=>$monto,'unidades'=>$unidades);
}
*/
function promedio_ventas_12( $id_cliente, $numero_para_promedio ){
	global $db_mysql;
	$monto = 0;
	$unidades = 0;
	$hoy = date('Y-n');
	$desde = strval(resta_meses($hoy,11));
	$desde = $desde . '-1 00:00:00';
	$sql="SELECT SUM(`monto_total`) as `monto`, SUM(`cantidad`) as `unidades` FROM `ventas` WHERE UNIX_TIMESTAMP(`fecha`) >= UNIX_TIMESTAMP('$desde') AND `id_cliente` = '$id_cliente' AND `anulada` = '0';";
//	echo "<p>$sql</p>";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$monto = floatval($dat['monto']/ $numero_para_promedio);
		$unidades = floatval($dat['unidades']/ $numero_para_promedio);
	}
	return array('monto'=>$monto,'unidades'=>$unidades);
}
/*
function ventas_ultimo_mes( $id_cliente ){
	global $db_mysql;
	$monto = 0;
	$unidades = 0;
	$sql="SELECT SUM(`monto_total`) as `monto`, SUM(`cantidad`) as `unidades` FROM `ventas` WHERE UNIX_TIMESTAMP(`fecha`) > (UNIX_TIMESTAMP(NOW())-2592000) AND `id_cliente` = '$id_cliente' AND `anulada` = '0';";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$monto = floatval($dat['monto']);
		$unidades = floatval($dat['unidades']);
	}
	return array('monto'=>$monto,'unidades'=>$unidades);
}
*/
function ventas_ultimo_mes( $id_cliente ){
	global $db_mysql;
	$monto = 0;
	$unidades = 0;
	$mes = date('n');
	$ano = date('Y');
	$porcentaje_periodo = date("d") / date("d",mktime(0,0,0,date('n')+1,0,date('Y')));
	$sql="SELECT SUM(`monto_total`) as `monto`, SUM(`cantidad`) as `unidades` FROM `ventas` WHERE `ano` = '$ano' AND `mes` = '$mes' AND `id_cliente` = '$id_cliente' AND `anulada` = '0';";
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

function ventas_x_cliente( $id_cliente, $fecha ){
	global $db_mysql;
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT SUM(`monto_total`) as `monto`, SUM(`cantidad`) as `unidades` FROM `ventas` WHERE `ano` = '$ano' AND `mes` = '$mes' AND `id_cliente` = '$id_cliente' AND `anulada` = '0';";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		return $dat;
	}
	return array('monto'=>0,'unidades'=>0);
}

function ventas_x_cliente_x_marca( $id_cliente, $fecha ){
	global $db_mysql;
	$ventas = array();
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT `marcas`.`id_marca` as `id_marca`, `marcas`.`nombre_marca` as `nombre_marca`, SUM(`ventas`.`cantidad`) as `unidades`, SUM(`ventas`.`monto_total`) as `monto` FROM `ventas`
	LEFT JOIN `marcas` ON `marcas`.`id_marca` = `ventas`.`id_marca`
	WHERE `ventas`.`ano` = '$ano' AND `ventas`.`mes` = '$mes' AND `ventas`.`id_cliente` = '$id_cliente' AND `ventas`.`anulada` = '0' GROUP BY `ventas`.`id_marca`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$ventas[strval($dat['id_marca'])] = $dat;
	}
	return $ventas;
}

function ventas_total_periodo($fecha){
	global $db_mysql;
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT SUM(`monto_total`) as `total` FROM `ventas` WHERE `ano` = '$ano' AND `mes` = '$mes' AND `anulada` = '0';";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		return $dat['total'];
	}else{
		return false;
	}
}

function ventas_total_principal($fecha){
	global $db_mysql;
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT SUM(`monto_total`) as `total` FROM `ventas` WHERE `ano` = '$ano' AND `mes` = '$mes' AND `anulada` = '0' AND `id_base` = '1' AND `id_tipo_venta` = '1' ;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		return $dat['total'];
	}else{
		return false;
	}
}

function ventas_total_ds($fecha){
	global $db_mysql;
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT SUM(`monto_total`) as `total` FROM `ventas` WHERE `ano` = '$ano' AND `mes` = '$mes' AND `anulada` = '0' AND `id_base` = '2';";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		return $dat['total'];
	}else{
		return false;
	}
}


function ventas_total_gm($fecha){
	global $db_mysql;
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT SUM(`monto_total`) as `total` FROM `ventas` WHERE `ano` = '$ano' AND `mes` = '$mes' AND `anulada` = '0' AND `id_tipo_venta` = '2' ;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		return $dat['total'];
	}else{
		return false;
	}
}

function ventas_total_x_vendedor($fecha){
	global $db_mysql;
	$ventas = array();
	$vendedores = get_vendedores_activos();
	foreach( $vendedores as $k=>$v ){
		$ventas[strval($k)] = 0;
	}
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT `codigo_vendedor`, SUM(`monto_total`) as `total` FROM `ventas`
	WHERE `ano` = '$ano' AND `mes` = '$mes' AND `anulada` = '0' GROUP BY `codigo_vendedor`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$codigo_vendedor = strval($dat['codigo_vendedor']);
		$ventas[$codigo_vendedor] = $dat['total'];
	}
	if( count($ventas)>0 ){
		return $ventas;
	}else{
		return false;
	}
}

function ventas_principal_x_vendedor($fecha){
	global $db_mysql;
	$ventas = array();
	$vendedores = get_vendedores_activos();
	foreach( $vendedores as $k=>$v ){
		$ventas[strval($k)] = 0;
	}
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT `codigo_vendedor`, SUM(`monto_total`) as `total` FROM `ventas`
	WHERE `ano` = '$ano' AND `mes` = '$mes' AND `anulada` = '0' AND `id_base` = '1' AND `id_tipo_venta` = '1' GROUP BY `codigo_vendedor`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$codigo_vendedor = strval($dat['codigo_vendedor']);
		$ventas[$codigo_vendedor] = $dat['total'];
	}
	if( count($ventas)>0 ){
		return $ventas;
	}else{
		return false;
	}
}

function ventas_ds_x_vendedor($fecha){
	global $db_mysql;
	$ventas = array();
	$vendedores = get_vendedores_activos();
	foreach( $vendedores as $k=>$v ){
		$ventas[strval($k)] = 0;
	}
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT `codigo_vendedor`, SUM(`monto_total`) as `total` FROM `ventas`
	WHERE `ano` = '$ano' AND `mes` = '$mes' AND `anulada` = '0' AND `id_base` = '2' GROUP BY `codigo_vendedor`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$codigo_vendedor = strval($dat['codigo_vendedor']);
		$ventas[$codigo_vendedor] = $dat['total'];
	}
	if( count($ventas)>0 ){
		return $ventas;
	}else{
		return false;
	}
}

function ventas_gm_x_vendedor($fecha){
	global $db_mysql;
	$ventas = array();
	$vendedores = get_vendedores_activos();
	foreach( $vendedores as $k=>$v ){
		$ventas[strval($k)] = 0;
	}
	$array_fecha = explode('-',$fecha);
	$ano = $array_fecha[0];
	$mes = $array_fecha[1];
	$sql="SELECT `codigo_vendedor`, SUM(`monto_total`) as `total` FROM `ventas`
	WHERE `ano` = '$ano' AND `mes` = '$mes' AND `anulada` = '0' AND `id_tipo_venta` = '2' GROUP BY `codigo_vendedor`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=mysqli_fetch_array($result)){
		$codigo_vendedor = strval($dat['codigo_vendedor']);
		$ventas[$codigo_vendedor] = $dat['total'];
	}
	if( count($ventas)>0 ){
		return $ventas;
	}else{
		return false;
	}
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






?>
