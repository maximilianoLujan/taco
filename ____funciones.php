<?php
/****** Funciones Generales *******/

function trimestre_mes($mes){
	$mes = intval($mes);
	if( $mes >= 1 && $mes <= 12 ){
		return ceil( $mes/3 );
	}
	return false;
}

function cantidad_importados($tabla,$db=''){
	global $db_mysql;
	if( strtolower($db) == 'principal' ){
		$sql="SELECT COUNT(*) FROM `$tabla` WHERE id_base = '1';";
	}elseif( strtolower($db) == 'ds' ){
		$sql="SELECT COUNT(*) FROM `$tabla` WHERE id_base = '2';";
	}else{
		$sql="SELECT COUNT(*) FROM `$tabla`;";
	}
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return $dat[0];
	}else{
		@mysqli_free_result($result);
		return 0;
	}
}
function importar_hasta($ultima_importacion,$dias_a_sumar){
	$array_fecha = explode('-',$ultima_importacion);
	$time = mktime(0,0,0,$array_fecha[1],$array_fecha[2],$array_fecha[0]);
	return date('Y-m-d',strtotime("+$dias_a_sumar days",$time));
}
function formatear_fecha($fecha){
	return date('Y-m-d',strtotime($fecha));
}
function fecha($fecha){
	$fecha = date('j/n/Y', strtotime( $fecha ) );
	return $fecha;
}
function numero_legible_redondeado( $valor ){
	$valor = floatval($valor);
	$valor = number_format( $valor , 0 , ',', '.');
	return $valor;
}
function es_positivo($n){
	if( is_numeric($n) && $n > 0){
		return true;
	}else{
		return false;
	}
}
function entero_positivo($n){
	if( is_numeric($n) && is_int($n) && $n > 0){
		return true;
	}else{
		return false;
	}
}
function validador($expresion,$string){
	$expresion = '/' . $expresion . '/';
	if( preg_match( "$expresion" , $string ) ){
		return true;
	}else{
		return false;
	}
}
function descuento_barrio( $barrio ){
	$barrio = strval( $barrio );
	if(preg_match('/^[ ]{0,5}\(.{1,99}\)/', $barrio, $matches)){
		$porcentaje_descuento_gm = floatval(str_replace(Array('(',')',' ',','),Array('','','','.'),$matches[0]));
	}else{
		$porcentaje_descuento_gm = 0;
	}
	return $porcentaje_descuento_gm;
}

function descuento_op_especial( $comentarios ){
	$comentarios = strval( $comentarios );
	if(preg_match('/^[ ]{0,5}\[.{1,99}\]/', $comentarios, $matches)){
		$porcentaje_descuento = floatval(str_replace(Array('[',']',' ',','),Array('','','','.'),$matches[0]));
	}else{
		$porcentaje_descuento = 0;
	}
	return $porcentaje_descuento;
}

/******* Funciones DB MySQL *********/

/******* ultimas importaciones *********/

function ultima_importacion_articulos(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `articulos` ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2000-01-01';
	}
}
function ultima_importacion_codigos_barra(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `codigos_barra` ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2000-01-01';
	}
}
function ultima_importacion_stock(){
	global $db_mysql;
	$sql="SELECT `fecha` FROM `stock` ORDER BY `fecha` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return $dat['fecha'];
	}else{
		@mysqli_free_result($result);
		return '2000-01-01';
	}
}
function ultima_importacion_clientes(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `clientes` ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2000-01-01';
	}
}
function ultima_importacion_ciudades(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `ciudades` ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2000-01-01';
	}
}
function ultima_importacion_grupos_super_rubros(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `grupos super rubro` ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2000-01-01';
	}
}
function ultima_importacion_marcas(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `marcas` ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2000-01-01';
	}
}
function ultima_importacion_provincias(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `provincias` ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2000-01-01';
	}
}
function ultima_importacion_rubros(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `rubros` ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2000-01-01';
	}
}
function ultima_importacion_sucursales(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `sucursales` ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2000-01-01';
	}
}
function ultima_importacion_super_rubros(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `super rubros` ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2000-01-01';
	}
}
function ultima_importacion_vendedores(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `vendedores` ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2000-01-01';
	}
}
function ultima_importacion_ventas_principal(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `ventas` WHERE `id_base` = '1' ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2011-06-01';
	}
}
function ultima_importacion_ventas_ds(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `ventas` WHERE `id_base` = '2' ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2011-06-01';
	}
}
function ultima_importacion_zonas(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `zonas` ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2000-01-01';
	}
}
function ultima_importacion_actividades(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `actividad` ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2000-01-01';
	}
}
function ultimo_asiento_sc(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `asientos` WHERE `sin_comprobante` = '1' AND `id_base` = '1' ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2013-01-01';
	}
}
function ultimo_asiento_sc_ds(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `asientos` WHERE `sin_comprobante` = '1' AND `id_base` = '2' ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2013-01-01';
	}
}
function ultima_importacion_asientos($caja=''){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `asientos` WHERE `id_base` = '1'";
    if($caja=='caja'){
        $sql .= " AND (`cuenta` LIKE '1110100%' OR `cuenta` = '11103001' OR `cuenta` LIKE '11102%')";
    }
    $sql .= " ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d H:i:s',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2013-01-01 00:00:00';
	}
}
function ultima_importacion_asientos_ds($caja=''){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `asientos` WHERE `id_base` = '2'";
    if($caja=='caja'){
        $sql .= " AND (`cuenta` LIKE '1110100%' OR `cuenta` = '11103001' OR `cuenta` LIKE '11102%')";
    }
    $sql .= " ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d H:i:s',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2013-01-01 00:00:00';
	}
}

function ultima_asignacion_centro_costo(){
	global $db_mysql;
	$sql="SELECT `codigoasiento` FROM `asignaciones_centros_costo` WHERE `id_base` = '1' ORDER BY `codigoasiento` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return intval($dat['codigoasiento']);
	}else{
		@mysqli_free_result($result);
		return 0;
	}
}
function ultima_asignacion_centro_costo_ds(){
	global $db_mysql;
	$sql="SELECT `codigoasiento` FROM `asignaciones_centros_costo` WHERE `id_base` = '2' ORDER BY `codigoasiento` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return intval($dat['codigoasiento']);
	}else{
		@mysqli_free_result($result);
		return 0;
	}
}
function ultima_importacion_cheques(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `cheques` WHERE `id_base` = '1' ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2013-01-01';
	}
}
function ultima_importacion_cheques_ds(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `cheques` WHERE `id_base` = '2' ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2013-01-01';
	}
}
function ultima_importacion_cuerpo_asientos(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `asientos` WHERE `id_base` = '1' ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2016-11-10';
	}
}
function ultima_importacion_comprobantes_principal(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `comprobantes` WHERE `id_base` = '1' ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2016-11-20';
	}
}
function ultima_importacion_cabeza_comprobantes($db){
	global $db_mysql;
	$sql="SELECT `fechamodificacion` FROM `cabeza_comprobantes` WHERE `id_base` = '$db' ORDER BY `fechamodificacion` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fechamodificacion']));
	}else{
		@mysqli_free_result($result);
		return '2016-01-01';
	}
}
function ultima_importacion_cuerpo_comprobantes($db){
	global $db_mysql;
	$sql="SELECT `fechamodificacion` FROM `cuerpo_comprobantes` WHERE `id_base` = '$db' ORDER BY `fechamodificacion` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fechamodificacion']));
	}else{
		@mysqli_free_result($result);
		return '2016-01-01';
	}
}
function ultima_importacion_cae_afip(){
	global $db_mysql;
	$sql="SELECT `fechahorasolicitud` FROM `caeafip` WHERE 1 ORDER BY `fechahorasolicitud` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return $dat['fechahorasolicitud'];
	}else{
		@mysqli_free_result($result);
		return '2000-1-1 00:00:00';
	}
}
function ultima_importacion_pedidos_pendientes(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `pendientes` ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2017-1-1';
	}
}

function borrar_pedidos_recibidos(){
	global $db_mysql;
	$sql="DELETE FROM `pendientes` WHERE `pedidos` = `recibidos` OR `anulado` = '1' OR `anulada` = '1';";
	if(mysqli_query($db_mysql,$sql)){
		@mysqli_free_result($result);
		return true;
	}
	return false;	
}
function borrar_pedidos(){
	global $db_mysql;
	$sql="DELETE FROM `pendientes` WHERE 1;";
	if(mysqli_query($db_mysql,$sql)){
		@mysqli_free_result($result);
		return true;
	}
	return false;	
}

function borrar_ubicaciones(){
	global $db_mysql;
	$sql="DELETE FROM `ubicaciones` WHERE 1;";
	if(mysqli_query($db_mysql,$sql)){
		@mysqli_free_result($result);
		return true;
	}
	return false;	
}

function borrar_clientes(){
	global $db_mysql;
	$sql="TRUNCATE `clientes`;";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function borrar_plan_de_cuentas(){
	global $db_mysql;
	$sql="DELETE FROM `plan de cuentas` WHERE 1;";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;	
}

function borrar_esp(){
	global $db_mysql;
	$sql="TRUNCATE `esp`;";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;	
}

function borrar_asientos_caja_6m(){
	global $db_mysql;
	$time = strtotime('-6 month',time());
	$fecha = date('Y-n-d 00:00:00',$time);
	$sql="DELETE FROM `asientos` WHERE `id_base` = '1' AND
    (`fecha_mod` >= '$fecha' OR `fecha` >= '$fecha') AND
    (`cuenta` LIKE '1110100%' OR `cuenta` = '11103001' OR `cuenta` LIKE '11102%');";
    //echo $sql;
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}
function borrar_asientos_caja_6m_ds(){
	global $db_mysql;
	$time = strtotime('-6 month',time());
	$fecha = date('Y-n-d 00:00:00',$time);
	$sql="DELETE FROM `asientos` WHERE `id_base` = '2' AND
    (`fecha_mod` >= '$fecha' OR `fecha` >= '$fecha') AND
    (`cuenta` LIKE '1110100%' OR `cuenta` = '11103001' OR `cuenta` LIKE '11102%');";
    //echo $sql;
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function ultima_importacion_bonificaciones_proveedor(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `bonificaciones` ORDER BY `fecha_mod` desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2012-1-1';
	}
}

function borrar_bonificaciones_proveedor(){
	global $db_mysql;
	$sql="DELETE FROM `bonificaciones` WHERE 1;";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;	
}

/// getters mysql

function get_articulos(){
	global $db_mysql;
	$sql="SELECT * FROM `articulos`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$id = strval($dat['id_articulo']);
		$articulos[$id] = $dat;
	}
	@mysqli_free_result($result);
	if(count($articulos)){
		return $articulos;
	}else{
		return false;
	}
}
function get_bases(){
	global $db_mysql;
	$sql="SELECT `id_base`, `nombre_base` FROM `base de datos`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$id = strval($dat['id_base']);
		$nombre = $dat['nombre_base'];
		$bases[$id] = $nombre;
	}
	@mysqli_free_result($result);
	if(count($bases)){
		return $bases;
	}else{
		return false;
	}
}
function get_clientes(){
	global $db_mysql;
	$sql="SELECT * FROM `clientes`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$id = strval($dat['id_cliente']);
		$clientes[$id] = $dat;
	}
	if(count($clientes)){
		return $clientes;
	}else{
		return false;
	}
}
function get_vendedores(){
	global $db_mysql;
	$sql="SELECT `codigo_vendedor`, `nombre_vendedor` FROM `vendedores`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$id = strval($dat['codigo_vendedor']);
		$nombre = $dat['nombre_vendedor'];
		$vendedores[$id] = $nombre;
	}
	@mysqli_free_result($result);
	if(count($vendedores)){
		return $vendedores;
	}else{
		return false;
	}
}
function get_grupos_super_rubro(){
	global $db_mysql;
	$sql="SELECT `id_grupo_super_rubro`, `nombre_grupo_super_rubro` FROM `grupos super rubro`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$id = strval($dat['id_grupo_super_rubro']);
		$nombre = $dat['nombre_grupo_super_rubro'];
		$grupos_super_rubro[$id] = $nombre;
	}
	@mysqli_free_result($result);
	if(count($grupos_super_rubro)){
		return $grupos_super_rubro;
	}else{
		return false;
	}
}
function get_super_rubros(){
	global $db_mysql;
	$sql="SELECT `id_super_rubro`, `id_grupo_super_rubro`, `nombre_super_rubro` FROM `super rubros`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$id = strval($dat['id_super_rubro']);
		$super_rubros[$id] = $dat;
	}
	@mysqli_free_result($result);
	if(count($super_rubros)){
		return $super_rubros;
	}else{
		return false;
	}
}
function get_rubros(){
	global $db_mysql;
	$sql="SELECT `id_rubro`, `id_super_rubro`, `nombre_rubro` FROM `rubros`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$id = strval($dat['id_rubro']);
		$rubros[$id] = $dat;
	}
	@mysqli_free_result($result);
	if(count($rubros)){
		return $rubros;
	}else{
		return false;
	}
}
function get_marcas(){
	global $db_mysql;
	$sql="SELECT `id_marca`, `nombre_marca` FROM `marcas`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$id = strval($dat['id_marca']);
		$nombre = $dat['nombre_marca'];
		$marcas[$id] = $nombre;
	}
	@mysqli_free_result($result);
	if(count($marcas)){
		return $marcas;
	}else{
		return false;
	}
}
function get_ciudades(){
	global $db_mysql;
	$sql="SELECT `id_ciudad`, `id_provincia`, `nombre_ciudad` FROM `ciudades`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$id = strval($dat['id_ciudad']);
		$ciudades[$id] = $dat;
	}
	@mysqli_free_result($result);
	if(count($ciudades)){
		return $ciudades;
	}else{
		return false;
	}
}
function get_provincias(){
	global $db_mysql;
	$sql="SELECT `id_provincia`, `nombre_provincia` FROM `provincias`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$id = strval($dat['id_provincia']);
		$provincias[$id] = $dat;
	}
	@mysqli_free_result($result);
	if(count($provincias)){
		return $provincias;
	}else{
		return false;
	}
}
function get_zonas(){
	global $db_mysql;
	$sql="SELECT `id_zona`, `nombre_zona` FROM `zonas`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$id = strval($dat['id_zona']);
		$zonas[$id] = $dat;
	}
	@mysqli_free_result($result);
	if(count($zonas)){
		return $zonas;
	}else{
		return false;
	}
}
function get_actividades(){
	global $db_mysql;
	$sql="SELECT `id_actividad`, `nombre_actividad` FROM `actividad`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$id = strval($dat['id_actividad']);
		$actividades[$id] = $dat;
	}
	@mysqli_free_result($result);
	if(count($actividades)){
		return $actividades;
	}else{
		return false;
	}
}
function get_sucursales(){
	global $db_mysql;
	$sql="SELECT `id_sucursal`, `nombre_sucursal` FROM `sucursales`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$id = strval($dat['id_sucursal']);
		$nombre = $dat['nombre_sucursal'];
		$sucursales[$id] = $nombre;
	}
	@mysqli_free_result($result);
	if(count($sucursales)){
		return $sucursales;
	}else{
		return false;
	}
}
function get_tipos_venta(){
	global $db_mysql;
	$sql="SELECT `id_tipo_venta`, `nombre_tipo_venta` FROM `GM`;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$id = strval($dat['id_tipo_venta']);
		$nombre = $dat['nombre_tipo_venta'];
		$tipos_venta[$id] = $nombre;
	}
	@mysqli_free_result($result);
	if(count($tipos_venta)){
		return $tipos_venta;
	}else{
		return false;
	}
}


/******* actualizar datos *********/

//si no existe lo creo, si existe lo actualizo

function actualizar_articulo($articulos){
	global $db_mysql;
	$id_articulo = strval($articulos['id_articulo']);
	$activo = strval($articulos['activo']);
	$nombre = str_replace("'",'\'', strval($articulos['nombre']) );
	$id_rubro = strval($articulos['id_rubro']);
	$id_marca = strval($articulos['id_marca']);
	$codigo_proveedor = strval($articulos['codigo_proveedor']);
	$codigo_particular = strval($articulos['codigo_particular']);
	$codigo_grupoalternativo = strval($articulos['codigo_grupoalternativo']);
	$fecha_mod = strval($articulos['fecha_mod']);
	$fecha_alta = strval($articulos['fecha_alta']);
	$precio_lista = floatval($articulos['precio_lista']);
	$sql="SELECT `id_articulo` FROM `articulos` WHERE `id_articulo` = '$id_articulo' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `articulos` SET `activo` = '$activo', `nombre_articulo` = '$nombre', `id_rubro` = '$id_rubro', `id_marca` = '$id_marca', `codigo_proveedor` = '$codigo_proveedor', `codigo_particular` = '$codigo_particular', `codigo_grupoalternativo` = '$codigo_grupoalternativo', `fecha_mod` = '$fecha_mod', `fecha_alta` = '$fecha_alta', `precio_lista` = '$precio_lista' WHERE `id_articulo` = '$id_articulo';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `articulos` SET `id_articulo` = '$id_articulo', `activo` = '$activo', `nombre_articulo` = '$nombre', `id_rubro` = '$id_rubro', `id_marca` = '$id_marca', `codigo_proveedor` = '$codigo_proveedor', `codigo_particular` = '$codigo_particular', `codigo_grupoalternativo` = '$codigo_grupoalternativo', `fecha_mod` = '$fecha_mod', `fecha_alta` = '$fecha_alta', `precio_lista` = '$precio_lista';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}

function actualizar_lote($lote){
	global $db_mysql;
	$codigo = strval($lote['codigo']);
	$codigocampo = strval($lote['codigocampo']);
	$valor = strval($lote['valor']);
	if( $codigocampo == '35' ){
		$sql="UPDATE `articulos` SET `pedir_minimo` = '$valor' WHERE `id_articulo` = '$codigo';";
	}elseif( $codigocampo == '36' ){
		$sql="UPDATE `articulos` SET `pedir_multiplo` = '$valor' WHERE `id_articulo` = '$codigo';";
	}else{
		return false;
	}
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}

	return false;
}

function actualizar_cb($cb){
	global $db_mysql;
	$codigo_barra = strval($cb['codigo_barra']);
	$codigo_barra = str_replace("'","\'",$codigo_barra);
	$id_articulo = strval($cb['id_articulo']);
	$fecha_mod = strval($cb['fecha_mod']);
	$sql="SELECT `id_codigo_barra` FROM `codigos_barra` WHERE `codigo_barra` = '$codigo_barra' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$id_codigo_barra = $dat['id_codigo_barra'];
		$sql="UPDATE `codigos_barra` SET `id_articulo` = '$id_articulo', `fecha_mod` = '$fecha_mod' WHERE `id_codigo_barra` = '$id_codigo_barra';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `codigos_barra` SET `id_articulo` = '$id_articulo', `codigo_barra` = '$codigo_barra', `fecha_mod` = '$fecha_mod';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}

function actualizar_stock($articulo,$fecha,$ano,$mes,$dia){
	global $db_mysql;
	$id_articulo = strval($articulo['id_articulo']);
	$id_marca = strval($articulo['id_marca']);
	$unidades = intval($articulo['unidades']);
	$monto_unidad = $articulo['monto'];
	$monto_total = $monto_unidad * $unidades;
	$codigodeposito = intval($articulo['codigodeposito']);
	$fecha = strval($fecha);
	$ano = intval($ano);
	$mes = intval($mes);
	$dia = intval($dia);
	$trimestre = trimestre_mes($mes);
	$sql="INSERT INTO `stock` SET `id_articulo` = '$id_articulo', `id_marca` = '$id_marca', `monto_unidad` = '$monto_unidad',  `monto_total` = '$monto_total', `unidades` = '$unidades', `fecha` = '$fecha', `ano` = '$ano', `mes` = '$mes', `dia` = '$dia', `trimestre` = '$trimestre', `codigodeposito` = '$codigodeposito';";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}
function actualizar_cobranzas($ventas){
	global $db_mysql;
	$ano = $ventas['ano'];
	$mes = $ventas['mes'];
	$trimestre = trimestre_mes($mes);
	$vendedor = $ventas['vendedor'];
	$total = $ventas['total'];
	$descuento = $ventas['descuento'];
	$dias = $ventas['dias'];
	$rentabilidad_porc = $ventas['rentabilidad_porc'];
	$rentabilidad_total = $ventas['rentabilidad_total'];
	$sql="SELECT `id_cobranza` FROM `cobranzas` WHERE `ano` = '$ano' AND `mes` = '$mes' AND `vendedor` = '$vendedor';";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `cobranzas` SET `total` = '$total', `descuento` = '$descuento', `dias` = '$dias', `rentabilidad_porc` = '$rentabilidad_porc', `rentabilidad_total` = '$rentabilidad_total', `trimestre` = '$trimestre' WHERE `ano` = '$ano' AND `mes` = '$mes' AND `vendedor` = '$vendedor';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `cobranzas` SET `ano` = '$ano', `mes` = '$mes', `trimestre` = '$trimestre', `vendedor` = '$vendedor', `total` = '$total', `descuento` = '$descuento', `dias` = '$dias', `rentabilidad_porc` = '$rentabilidad_porc', `rentabilidad_total` = '$rentabilidad_total';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}
function actualizar_deudas_clientes($deuda,$fecha,$ano,$mes,$dia){
	global $db_mysql;
	$tipo = strval($deuda['tipo_comprobante']);
	$numero = strval($deuda['numero_comprobante']);
	$cliente = strval($deuda['id_cliente']);
	$db = strval($deuda['id_base']);
	$deposito = strval($deuda['codigo_deposito']);
	$vendedor = strval($deuda['codigo_vendedor']);
	$total = strval($deuda['total']);
	$pagado = strval($deuda['pagado']);
	$valor_deuda = strval($deuda['deuda']);
	$dias_deuda = floatval($deuda['dias_deuda']);
	$fecha = strval($fecha);
	$ano = intval($ano);
	$mes = intval($mes);
	$dia = intval($dia);
	$trimestre = trimestre_mes($mes);
	$sql="INSERT INTO `deudas_clientes` SET `tipo_comprobante` = '$tipo', `numero_comprobante` = '$numero', `id_cliente` = '$cliente', `id_base` = '$db', `codigo_deposito` = '$deposito', `codigo_vendedor` = '$vendedor', `total` = '$total', `pagado` = '$pagado', `deuda` = '$valor_deuda', `dias_deuda` = '$dias_deuda', `fecha` = '$fecha', `ano` = '$ano', `mes` = '$mes', `dia` = '$dia', `trimestre` = '$trimestre';";
	if( $cliente == '01065' || $cliente == '03849' ){  // parche para sacar las deudas de los clientes distrisuper pico y distrisuper mdp
		return true;
	}elseif(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function actualizar_deuda_proveedor($deuda,$fecha,$ano,$mes,$dia){
	global $db_mysql;
	$id_base = strval($deuda['id_base']);
	$tipo = strval($deuda['tipo']);
	$numero = strval($deuda['numero']);
	$codigo_proveedor = strval($deuda['codigo_proveedor']);
	$razon_social = strval($deuda['razon_social']);
	$codigo_vendedor = strval($deuda['codigo_vendedor']);
	$id_provincia = strval($deuda['id_provincia']);
	$total = floatval($deuda['total']);
	$pagado = floatval($deuda['pagado']);
//	$deuda = floatval( $total - $pagado );
	$deuda = floatval( $deuda['deuda'] );
	$fecha = strval($fecha);
	$ano = intval($ano);
	$mes = intval($mes);
	$dia = intval($dia);
	$trimestre = trimestre_mes($mes);
	$sql="INSERT INTO `deudas_proveedores` SET `id_base` = '$id_base', `tipo` = '$tipo', `numero` = '$numero', `codigo_proveedor` = '$codigo_proveedor', `razon_social` = '$razon_social', `codigo_vendedor` = '$codigo_vendedor', `id_provincia` = '$id_provincia', `total` = '$total', `pagado` = '$pagado', `deuda` = '$deuda', `fecha` = '$fecha', `ano` = '$ano', `mes` = '$mes', `dia` = '$dia', `trimestre` = '$trimestre';";
	if( $codigo_proveedor == '01065' || $codigo_proveedor == '03849' ){  // parche para sacar las deudas de los clientes distrisuper pico y distrisuper mdp
		return true;
	}elseif(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function actualizar_cliente($cliente){
	global $db_mysql;
	$id_cliente = strval($cliente['id_cliente']);
	$codigo_particular = strval($cliente['codigo_particular']);
	$nombre = strval($cliente['nombre']);
	$activo = intval($cliente['activo']);
	$activo_taco = activo_taco( $id_cliente );
	$cliente_activo_con_excel = cliente_activo_con_excel( $id_cliente );
	$es_excel = es_cliente_excel( $codigo_particular );
	$id_ciudad = strval($cliente['id_ciudad']);
	$id_provincia = strval($cliente['id_provincia']);
 	$id_ciudad_provincia = $id_ciudad . $id_provincia;
	$id_zona = strval($cliente['id_zona']);
	$id_actividad = strval($cliente['id_actividad']);
	$codigo_vendedor = strval($cliente['codigo_vendedor']);
	$barrio = strval($cliente['barrio']);
	$bonificacion = strval($cliente['bonificacion']);
	$porcentaje_descuento_gm = descuento_barrio($barrio);
	$fecha_mod = strval($cliente['fecha_mod']);
	$sql="SELECT `id_cliente` FROM `clientes` WHERE `id_cliente` = '$id_cliente' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `clientes` SET 
			`codigo_particular` = '$codigo_particular', 
			`nombre_cliente` = '$nombre', 
			`activo` = '$activo', 
			`activo_taco` = '$activo_taco', 
			`cliente_activo_con_excel` = '$cliente_activo_con_excel', 
			`es_excel` = '$es_excel',
			`id_ciudad_provincia` = '$id_ciudad_provincia', 
			`id_ciudad` = '$id_ciudad', 
			`id_provincia` = '$id_provincia', 
			`id_zona` = '$id_zona', 
			`id_actividad` = '$id_actividad', 
			`codigo_vendedor` = '$codigo_vendedor', 
			`porcentaje_descuento_gm` = '$porcentaje_descuento_gm', 
			`barrio` = '$barrio', 
			`bonificacion` = '$bonificacion',
			`fecha_mod` = '$fecha_mod' 
			WHERE `id_cliente` = '$id_cliente';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}else{
			echo "<p>$sql</p>";
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `clientes` SET 
			`id_cliente` = '$id_cliente', 
			`codigo_particular` = '$codigo_particular', 
			`nombre_cliente` = '$nombre', 
			`activo` = '$activo', 
			`activo_taco` = '$activo_taco', 
			`cliente_activo_con_excel` = '$cliente_activo_con_excel', 
			`es_excel` = '$es_excel',
			`id_ciudad_provincia` = '$id_ciudad_provincia', 
			`id_ciudad` = '$id_ciudad', 
			`id_provincia` = '$id_provincia', 
			`id_zona` = '$id_zona', 
			`id_actividad` = '$id_actividad', 
			`codigo_vendedor` = '$codigo_vendedor', 
			`porcentaje_descuento_gm` = '$porcentaje_descuento_gm', 
			`barrio` = '$barrio', 
			`bonificacion` = '$bonificacion',
			`fecha_mod` = '$fecha_mod';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}else{
			echo "<p>$sql</p>";
		}
	}
	return false;
}

function actualizar_asociacion_cliente($asociacion){
	global $db_mysql;
	$codigo = $asociacion['codigo'];
	$fric_rot = @$asociacion['CUENTA OP FRIC-ROT'];
	$distrisuper = @$asociacion['CUENTA OP DISTRISUPER'];
	$saldo_inicial_distrisuper_fecha = @$asociacion['saldo-inicial-distrisuper']['fecha'];
	if( !$saldo_inicial_distrisuper_fecha ){
		$saldo_inicial_distrisuper_fecha = '2019-01-01';
	}
	$saldo_inicial_distrisuper_monto = floatval(@$asociacion['saldo-inicial-distrisuper']['monto']);
	$saldo_inicial_fric_rot_fecha = @$asociacion['saldo-inicial-fric-rot']['fecha'];
	if( !$saldo_inicial_fric_rot_fecha ){
		$saldo_inicial_fric_rot_fecha = '2019-01-01';
	}
	$saldo_inicial_fric_rot_monto = floatval(@$asociacion['saldo-inicial-fric-rot']['monto']);
	$sql="SELECT `id_asociacion` FROM `asociaciones_clientes` WHERE `codigo` = '$codigo' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return true;
	}else{
		@mysqli_free_result($result);
		$clave = microtime() . '-' . rand() . '-' . rand();
		$auth = hash('sha256',$clave);
		$link = "https://precios-congelados-distrisuper.sytes.net/movimientos-cliente.php?id=" . $codigo . '&h=' . $auth;
		$sql="INSERT INTO `asociaciones_clientes` SET
		`codigo` = '$codigo',
		`cuenta_op_fric_rot` = '$fric_rot',
		`cuenta_op_distrisuper` = '$distrisuper',
		`saldo_inicial_distrisuper_fecha` = '$saldo_inicial_distrisuper_fecha',
		`saldo_inicial_distrisuper_monto` = '$saldo_inicial_distrisuper_monto',
		`saldo_inicial_fric_rot_fecha` = '$saldo_inicial_fric_rot_fecha',
		`saldo_inicial_fric_rot_monto` = '$saldo_inicial_fric_rot_monto',
		`auth` = '$auth',
		`link` = '$link';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}else{
			echo "<p>$sql</p>";
		}
	}
	return false;
}

function actualizar_ciudad($ciudad){
	global $db_mysql;
	$id_ciudad = strval($ciudad['id_ciudad']);
	$id_provincia = strval($ciudad['id_provincia']);
	$id_ciudad_provincia = $id_ciudad . $id_provincia;/// parche para no tener problemas con ciudades que repiten el id
	$nombre = strval($ciudad['nombre']);
	$fecha_mod = strval($ciudad['fecha_mod']);
	$sql="SELECT `id_ciudad_provincia` FROM `ciudades` WHERE `id_ciudad_provincia` = '$id_ciudad_provincia' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `ciudades` SET `nombre_ciudad` = '$nombre', `fecha_mod` = '$fecha_mod' WHERE `id_ciudad_provincia` = '$id_ciudad_provincia';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `ciudades` SET `id_ciudad_provincia` = '$id_ciudad_provincia', `id_ciudad` = '$id_ciudad', `id_provincia` = '$id_provincia', `nombre_ciudad` = '$nombre', `fecha_mod` = '$fecha_mod';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}
function actualizar_grupo_super_rubro($grupo_super_rubro){
	global $db_mysql;
	$id_grupo_super_rubro = strval($grupo_super_rubro['id_grupo_super_rubro']);
	$nombre = strval($grupo_super_rubro['nombre']);
	$fecha_mod = strval($grupo_super_rubro['fecha_mod']);
	$sql="SELECT `id_grupo_super_rubro` FROM `grupos super rubro` WHERE `id_grupo_super_rubro` = '$id_grupo_super_rubro' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `grupos super rubro` SET `nombre_grupo_super_rubro` = '$nombre', `fecha_mod` = '$fecha_mod' WHERE `id_grupo_super_rubro` = '$id_grupo_super_rubro';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `grupos super rubro` SET `id_grupo_super_rubro` = '$id_grupo_super_rubro', `nombre_grupo_super_rubro` = '$nombre', `fecha_mod` = '$fecha_mod';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}
function actualizar_marca($marca){
	global $db_mysql;
	$id_marca = strval($marca['id_marca']);
	$nombre = strval($marca['nombre']);
	$fecha_mod = strval($marca['fecha_mod']);
	$activa = intval($marca['activa']);
	$sql="SELECT `id_marca` FROM `marcas` WHERE `id_marca` = '$id_marca' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `marcas` SET `nombre_marca` = '$nombre', `fecha_mod` = '$fecha_mod', `activa` = '$activa' WHERE `id_marca` = '$id_marca';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `marcas` SET `id_marca` = '$id_marca', `nombre_marca` = '$nombre', `fecha_mod` = '$fecha_mod', `activa` = '$activa';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}
function actualizar_provincia($provincia){
	global $db_mysql;
	$id_provincia = strval($provincia['id_provincia']);
	$nombre = strval($provincia['nombre']);
	$fecha_mod = strval($provincia['fecha_mod']);
	$sql="SELECT `id_provincia` FROM `provincias` WHERE `id_provincia` = '$id_provincia' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `provincias` SET `nombre_provincia` = '$nombre', `fecha_mod` = '$fecha_mod' WHERE `id_provincia` = '$id_provincia';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `provincias` SET `id_provincia` = '$id_provincia', `nombre_provincia` = '$nombre', `fecha_mod` = '$fecha_mod';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}
function actualizar_rubro($rubro){
	global $db_mysql;
	$id_rubro = strval($rubro['id_rubro']);
	$id_super_rubro = strval($rubro['id_super_rubro']);
	$nombre = strval($rubro['nombre']);
	$fecha_mod = strval($rubro['fecha_mod']);
	$sql="SELECT `id_rubro` FROM `rubros` WHERE `id_rubro` = '$id_rubro' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `rubros` SET `id_super_rubro` = '$id_super_rubro', `nombre_rubro` = '$nombre', `fecha_mod` = '$fecha_mod' WHERE `id_rubro` = '$id_rubro';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `rubros` SET `id_rubro` = '$id_rubro', `id_super_rubro` = '$id_super_rubro', `nombre_rubro` = '$nombre', `fecha_mod` = '$fecha_mod';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}
function actualizar_sucursal($sucursal){
	global $db_mysql;
	$id_sucursal = strval(intval($sucursal['id_sucursal']));
	$nombre = strval($sucursal['nombre']);
	$sql="SELECT `id_sucursal` FROM `sucursales` WHERE `id_sucursal` = '$id_sucursal' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `sucursales` SET `nombre_sucursal` = '$nombre' WHERE `id_sucursal` = '$id_sucursal';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `sucursales` SET `id_sucursal` = '$id_sucursal', `nombre_sucursal` = '$nombre';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}
function actualizar_base($base){
	global $db_mysql;
	$id_base = $base['id_base'];
	$nombre = strval($base['nombre']);
	$sql="SELECT `id_base` FROM `base de datos` WHERE `id_base` = '$id_base' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `base de datos` SET `nombre_base` = '$nombre' WHERE `id_base` = '$id_base';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `base de datos` SET `id_base` = '$id_base', `nombre_base` = '$nombre';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}
function actualizar_tipo_venta($tipo_venta){
	global $db_mysql;
	$id_tipo_venta = $tipo_venta['id_tipo_venta'];
	$nombre = strval($tipo_venta['nombre']);
	$sql="SELECT `id_tipo_venta` FROM `GM` WHERE `id_tipo_venta` = '$id_tipo_venta' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `GM` SET `nombre_tipo_venta` = '$nombre' WHERE `id_tipo_venta` = '$id_tipo_venta';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `GM` SET `id_tipo_venta` = '$id_tipo_venta', `nombre_tipo_venta` = '$nombre';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}
function actualizar_super_rubro($super_rubro){
	global $db_mysql;
	$id_super_rubro = strval($super_rubro['id_super_rubro']);
	$id_grupo_super_rubro = strval($super_rubro['id_grupo_super_rubro']);
	$nombre = strval($super_rubro['nombre']);
	$fecha_mod = strval($super_rubro['fecha_mod']);
	$sql="SELECT `id_super_rubro` FROM `super rubros` WHERE `id_super_rubro` = '$id_super_rubro' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `super rubros` SET `id_grupo_super_rubro` = '$id_grupo_super_rubro', `nombre_super_rubro` = '$nombre', `fecha_mod` = '$fecha_mod' WHERE `id_super_rubro` = '$id_super_rubro';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `super rubros` SET `id_super_rubro` = '$id_super_rubro', `id_grupo_super_rubro` = '$id_grupo_super_rubro', `nombre_super_rubro` = '$nombre', `fecha_mod` = '$fecha_mod';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}
function actualizar_vendedor($vendedor){
	global $db_mysql;
	$codigo_vendedor = strval($vendedor['codigo_vendedor']);
	$nombre = strval($vendedor['nombre']);
	$activo = intval($vendedor['activo']);
	$es_vendedor = intval($vendedor['es_vendedor']);
	$fecha_mod = strval($vendedor['fecha_mod']);
	$sql="SELECT `codigo_vendedor` FROM `vendedores` WHERE `codigo_vendedor` = '$codigo_vendedor' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `vendedores` SET `nombre_vendedor` = '$nombre', `activo` = '$activo', `es_vendedor` = '$es_vendedor', `fecha_mod` = '$fecha_mod' WHERE `codigo_vendedor` = '$codigo_vendedor';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `vendedores` SET `codigo_vendedor` = '$codigo_vendedor', `activo` = '$activo', `es_vendedor` = '$es_vendedor', `nombre_vendedor` = '$nombre', `fecha_mod` = '$fecha_mod';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}

function actualizar_asiento($asiento){
	if( strlen(strval($asiento['codigoasiento'])) > 0 && strlen(strval($asiento['fecha'])) > 7 && strlen(strval($asiento['numeroasiento'])) > 0 ){
		global $db_mysql;
		$codigoasiento = strval($asiento['codigoasiento']);
		$numeroasiento = strval($asiento['numeroasiento']);
		$observaciones = substr(strval($asiento['observaciones']),0,254);
		$fecha = strval($asiento['fecha']);
		$codigoejercicio = strval($asiento['codigoejercicio']);
		$linea = strval($asiento['linea']);
		$cuenta = strval($asiento['cuenta']);
		$monto = floatval($asiento['monto']);
		$esdebe = strval($asiento['esdebe']);
		$fecha_mod = strval($asiento['fecha_mod']);
		// $mod_cabeza = strval($asiento['mod_cabeza']);
		// $mod_cuerpo = strval($asiento['mod_cuerpo']);
		// if( strtotime($mod_cabeza) > strtotime($mod_cuerpo) ){
		// 	$fecha_mod = explode(' ', $mod_cabeza)[0] . ' 00:00:00';
		// }else{
		// 	$fecha_mod = explode(' ', $mod_cuerpo)[0] . ' 00:00:00';
		// }
		$tipocomprobante = substr(strval($asiento['tipocomprobante']),0,14);
		$numerocomprobante = substr(strval($asiento['numerocomprobante']),0,21);
		$id_base = intval($asiento['id_base']);
		$fecha_1 = explode(' ',$fecha);
		$fecha_2 = explode('-',$fecha_1[0]);
		$ano = intval($fecha_2[0]);
		$mes = intval($fecha_2[1]);
		$dia = intval($fecha_2[2]);
		$trimestre = intval(trimestre_mes($mes));
		$sql="SELECT `id_asiento` FROM `asientos` WHERE `codigoasiento` = '$codigoasiento' AND `codigoejercicio` = '$codigoejercicio' AND `linea` = '$linea' AND `id_base` = '$id_base' LIMIT 1;";
		$result=mysqli_query($db_mysql,$sql);
		if($dat=@mysqli_fetch_array($result)){
			@mysqli_free_result($result);
			$id_asiento = $dat['id_asiento'];
			$obs_2 = strtoupper($observaciones);
			if( $ano > 2017 && ( validador('REFUNDICION', $obs_2) || validador('^ASIENTO DE CIERRE', $obs_2) || validador('^ASIENTO DE APERTURA', $obs_2)) ){
				$tipo = 0;
			}else{
				$tipo = 1;
			}
			$sql="UPDATE `asientos` SET `numeroasiento` = '$numeroasiento',
			`observaciones` = '$observaciones', 
			`fecha` = '$fecha', 
			`codigoejercicio` = '$codigoejercicio', 
			`ano` = '$ano', 
			`mes` = '$mes', 
			`trimestre` = '$trimestre', 
			`fecha_mod` = '$fecha_mod', 
			`cuenta` = '$cuenta', 
			`monto` = '$monto', 
			`esdebe` = '$esdebe', 
			`tipocomprobante` = '$tipocomprobante', 
			`numerocomprobante` = '$numerocomprobante', 
			`id_base` = '$id_base', 
			`tipo` = '$tipo' 
			WHERE `id_asiento` = '$id_asiento' LIMIT 1;";
	//		echo "<p>$sql</p>";
			if(mysqli_query($db_mysql,$sql)){
				return true;
			}else{
				echo "<p>$sql</p>";
			}
		}else{
			@mysqli_free_result($result);
			$obs_2 = strtoupper($observaciones);
			if( $ano > 2017 && ( validador('REFUNDICION', $obs_2) || validador('^ASIENTO DE CIERRE', $obs_2) || validador('^ASIENTO DE APERTURA', $obs_2)) ){
				$tipo = 0;
			}else{
				$tipo = 1;
			}
			$sql="INSERT INTO `asientos` SET 
			`codigoasiento` = '$codigoasiento', 
			`numeroasiento` = '$numeroasiento', 
			`observaciones` = '$observaciones', 
			`fecha` = '$fecha', 
			`codigoejercicio` = '$codigoejercicio', 
			`ano` = '$ano', 
			`mes` = '$mes', 
			`trimestre` = '$trimestre', 
			`fecha_mod` = '$fecha_mod', 
			`linea` = '$linea', 
			`cuenta` = '$cuenta', 
			`monto` = '$monto', 
			`esdebe` = '$esdebe', 
			`tipocomprobante` = '$tipocomprobante', 
			`numerocomprobante` = '$numerocomprobante', 
			`id_base` = '$id_base', 
			`tipo` = '$tipo';";
			if(mysqli_query($db_mysql,$sql)){
				return true;
			}else{
				echo "<p>$sql</p>";
				return false;
			}
		}
		return false;
	}else{
		return true;
	}
}

function actualizar_asiento_sin_comprobante($asiento,$db){
	global $db_mysql;
	$codigoasiento = strval($asiento);
	$db = intval($db);
	$sql="UPDATE `asientos` SET `sin_comprobante` = '1' WHERE `codigoasiento` = '$codigoasiento' AND `id_base` = '$db';";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function actualizar_asignacion_centro_costo($acc,$id_base){
	global $db_mysql;
	$codigoasiento = $acc['codigoasiento'];
	$linea = $acc['linea'];
	$codigocentroscosto = $acc['codigocentroscosto'];
	$porcentaje = $acc['porcentaje'];
	$monto = $acc['monto'];
	$sql="SELECT `id_acc` FROM `asignaciones_centros_costo` WHERE `codigoasiento` = '$codigoasiento' AND `linea` = '$linea' AND `codigocentroscosto` = '$codigocentroscosto' AND `id_base` = '$id_base' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$id_asiento = $dat['id_acc'];
		$sql="UPDATE `asignaciones_centros_costo` SET `porcentaje` = '$porcentaje', `monto` = '$monto'LIMIT 1;";
//		echo "<p>$sql</p>";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `asignaciones_centros_costo` SET `codigoasiento` = '$codigoasiento', `linea` = '$linea', `id_base` = '$id_base', `codigocentroscosto` = '$codigocentroscosto', `porcentaje` = '$porcentaje', `monto` = '$monto';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}

function actualizar_cheque($cheque){
	global $db_mysql;
	$codigocheque = strval($cheque['codigocheque']);
	$numerocheque = strval($cheque['numerocheque']);
	$nombre = strval($cheque['nombre']);
	$monto = floatval($cheque['monto']);
	$entregado = intval($cheque['entregado']);
	$caja = strval($cheque['caja']);
	$fecha = strval($cheque['fecha']);
	$fecha_mod = strval($cheque['fecha_mod']);
	$id_base = intval($cheque['id_base']);
/*	$fecha_1 = explode(' ',$fecha);
	$fecha_2 = explode('-',$fecha_1[0]);
	$ano = intval($fecha_2[0]);
	$mes = intval($fecha_2[1]);
	$dia = intval($fecha_2[2]);
	$trimestre = trimestre_mes($mes);*/
	$sql="SELECT `numerocheque` FROM `cheques` WHERE `codigocheque` = '$codigocheque' AND `id_base` = '$id_base' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `cheques` SET `numerocheque` = '$numerocheque', `nombre` = '$nombre', `monto` = '$monto', `entregado` = '$entregado', `caja` = '$caja', `fecha` = '$fecha', `fecha_mod` = '$fecha_mod' WHERE `codigocheque` = '$codigocheque' AND `id_base` = '$id_base' LIMIT 1;";
//		echo "<p>$sql</p>";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `cheques` SET `codigocheque` = '$codigocheque', `numerocheque` = '$numerocheque', `nombre` = '$nombre', `monto` = '$monto', `entregado` = '$entregado', `caja` = '$caja', `fecha` = '$fecha', `fecha_mod` = '$fecha_mod', `id_base` = '$id_base';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}

function actualizar_cuerpo_asiento($asiento){
	global $db_mysql;
	$codigoasiento = strval($asiento['codigoasiento']);
	$linea = strval($asiento['linea']);
	$cuenta = strval($asiento['cuenta']);
	$monto = floatval($asiento['monto']);
	$esdebe = strval($asiento['esdebe']);
	$fecha_mod = strval($asiento['mod_cuerpo']);
	$id_base = intval($asiento['id_base']);
	$sql="SELECT `codigoasiento` FROM `asientos` WHERE `codigoasiento` = '$codigoasiento' AND `linea` = '$linea' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `asientos` SET `fecha_mod` = '$fecha_mod', `cuenta` = '$cuenta', `monto` = '$monto', `esdebe` = '$esdebe', `id_base` = '$id_base' WHERE `codigoasiento` = '$codigoasiento' AND `linea` = '$linea';";
//		echo "<p>$sql</p>";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `asientos` SET `codigoasiento` = '$codigoasiento', `fecha_mod` = '$fecha_mod', `linea` = '$linea', `cuenta` = '$cuenta', `monto` = '$monto', `esdebe` = '$esdebe', `id_base` = '$id_base';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}
function actualizar_cabeza_asiento($asiento){
	global $db_mysql;
	$codigoasiento = strval($asiento['codigoasiento']);
	$codigoejercicio = strval($asiento['codigoejercicio']);
	$numeroasiento = strval($asiento['numeroasiento']);
	$observaciones = strval($asiento['observaciones']);
	$fecha = strval($asiento['fecha']);
	$codigoagrupacion = strval($asiento['codigoagrupacion']);
	$fecha_mod = strval($asiento['mod_cabeza']);
	$id_base = intval($asiento['id_base']);
	$fecha_1 = explode(' ',$fecha);
	$fecha_2 = explode('-',$fecha_1[0]);
	$ano = intval($fecha_2[0]);
	$mes = intval($fecha_2[1]);
	$dia = intval($fecha_2[2]);
	$trimestre = trimestre_mes($mes);
	$sql="UPDATE `asientos` SET `codigoejercicio` = '$codigoejercicio', `numeroasiento` = '$numeroasiento', `observaciones` = '$observaciones', `fecha` = '$fecha', `codigoagrupacion` = '$codigoagrupacion', `ano` = '$ano', `mes` = '$mes', `trimestre` = '$trimestre' WHERE `codigoasiento` = '$codigoasiento';";
//	echo "<p>$sql</p>";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function actualizar_comprobante($comprobante){
	global $db_mysql;
	$tipocomprobante = strval($comprobante['tipocomprobante']);
	$numerocomprobante = strval($comprobante['numerocomprobante']);
	$puntoventa = strval($comprobante['puntoventa']);
	$total = floatval($comprobante['total']);
	$pagado = floatval($comprobante['pagado']);
	$fecha = strval($comprobante['fecha']);
	$fecha_mod = strval($comprobante['fecha_mod']);
	$id_base = intval($comprobante['id_base']);
	$fecha_1 = explode(' ',$fecha);
	$fecha_2 = explode('-',$fecha_1[0]);
	$ano = intval($fecha_2[0]);
	$mes = intval($fecha_2[1]);
	$dia = intval($fecha_2[2]);
	$trimestre = trimestre_mes($mes);
	$sql="SELECT `id_comprobante` FROM `comprobantes` WHERE `tipocomprobante` = '$tipocomprobante' AND `numerocomprobante` = '$numerocomprobante' AND `id_base` = '$id_base' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `comprobantes` SET `puntoventa` = '$puntoventa', `total` = '$total', `pagado` = '$pagado', `fecha` = '$fecha', `ano` = '$ano', `mes` = '$mes', `dia` = '$dia', `trimestre` = '$trimestre', `fecha_mod` = '$fecha_mod' WHERE `tipocomprobante` = '$tipocomprobante' AND `numerocomprobante` = '$numerocomprobante' AND `id_base` = '$id_base';";
//		echo "<p>$sql</p>";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `comprobantes` SET `tipocomprobante` = '$tipocomprobante', `numerocomprobante` = '$numerocomprobante', `puntoventa` = '$puntoventa', `total` = '$total', `pagado` = '$pagado', `fecha` = '$fecha', `ano` = '$ano', `mes` = '$mes', `dia` = '$dia', `trimestre` = '$trimestre', `fecha_mod` = '$fecha_mod', `id_base` = '$id_base';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}

function insertar_redi($comprobante){
	global $db_mysql;
	$id = $comprobante['id'];
	$remitonro = $comprobante['remitonro'];
	$clienteid = $comprobante['clienteid'];
	$path = $comprobante['path'];
	$Fecha = $comprobante['Fecha'];
	$foo = explode('/', $comprobante['Fecha']);
	$date = $foo[2] . '-' . $foo[1] . '-' . $foo[0];
	$estado = $comprobante['estado'];
	@mysqli_free_result($result);
	$sql="INSERT INTO `redi` SET
	`id` = '$id',
	`remitonro` = '$remitonro',
	`clienteid` = '$clienteid',
	`path` = '$path',
	`Fecha` = '$Fecha',
	`date` = '$date',
	`estado` = '$estado';";
//	echo "<p>$sql</p>";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function insertar_remito_e($comprobante){
	global $db_mysql;
	$IDRemito = $comprobante['IDRemito'];
	$PtoVtaNoOficial = $comprobante['PtoVtaNoOficial'];
	$NroRemNoOficial = $comprobante['NroRemNoOficial'];
	$IDCliente = $comprobante['IDCliente'];
	$NroEmpresa = $comprobante['NroEmpresa'];
	$Nro_Rto = $comprobante['Nro_Rto'];
	$FechaRem = $comprobante['FechaRem'];
	$estado = $comprobante['estado'];
	$Estado_Web = $comprobante['Estado_Web'];
	@mysqli_free_result($result);
	$sql="INSERT INTO `RemitosE` SET
	`IDRemito` = '$IDRemito',
	`PtoVtaNoOficial` = '$PtoVtaNoOficial',
	`NroRemNoOficial` = '$NroRemNoOficial',
	`IDCliente` = '$IDCliente',
	`NroEmpresa` = '$NroEmpresa',
	`Nro_Rto` = '$Nro_Rto',
	`FechaRem` = '$FechaRem',
	`estado` = '$estado',
	`Estado_Web` = '$Estado_Web';";
//	echo "<p>$sql</p>";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function insertar_remito_e_det($comprobante){
	global $db_mysql;
	$IdRemDet = $comprobante['IdRemDet'];
	$IdRem = $comprobante['IdRem'];
	$Codigo = $comprobante['Codigo'];
	$Cantidad = $comprobante['Cantidad'];
	@mysqli_free_result($result);
	$sql="INSERT INTO `RemitosEDet` SET
	`IdRemDet` = '$IdRemDet',
	`IdRem` = '$IdRem',
	`Codigo` = '$Codigo',
	`Cantidad` = '$Cantidad';";
//	echo "<p>$sql</p>";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function actualizar_cabeza_comprobante($comprobante){
	global $db_mysql;
	$tipocomprobante = $comprobante['tipocomprobante'];
	$numerocomprobante = $comprobante['numerocomprobante'];
	$codigocliente = $comprobante['codigocliente'];
	$fechacomprobante = $comprobante['fechacomprobante'];
	$razonsocial = $comprobante['razonsocial'];
	$direccion = $comprobante['direccion'];
	$porciva1 = $comprobante['porciva1'];
	$porciva2 = $comprobante['porciva2'];
	$iva1 = $comprobante['iva1'];
	$iva2 = $comprobante['iva2'];
	$total = $comprobante['total'];
	$pagado = $comprobante['pagado'];
	$cuentacorriente = $comprobante['cuentacorriente'];
	$hora = $comprobante['hora'];
	$codigousuario = $comprobante['codigousuario'];
	$tipoiva = $comprobante['tipoiva'];
	$remitofacturado = $comprobante['remitofacturado'];
	$comentarios = $comprobante['comentarios'];
	$telefono = $comprobante['telefono'];
	$fechavencimiento = $comprobante['fechavencimiento'];
	$imprime = $comprobante['imprime'];
	$anulada = $comprobante['anulada'];
	$cuit = $comprobante['cuit'];
	$compra = $comprobante['compra'];
	$codigotransporte = $comprobante['codigotransporte'];
	$montotransporte = $comprobante['montotransporte'];
	$codigomultiplazo = $comprobante['codigomultiplazo'];
	$exento = $comprobante['exento'];
	$clasecomprobante = $comprobante['clasecomprobante'];
	$codigousuario2 = $comprobante['codigousuario2'];
	$coeficienteiva = $comprobante['coeficienteiva'];
	$fechamodificacion = $comprobante['fechamodificacion'];
	$desccomprobante = $comprobante['desccomprobante'];
	$codigomoneda = $comprobante['codigomoneda'];
	$cotizacion = $comprobante['cotizacion'];
	$numerotransaccion = $comprobante['numerotransaccion'];
	$cantidadbultos = $comprobante['cantidadbultos'];
	$nropuntodeventa = $comprobante['nropuntodeventa'];
	$codigoproyecto = $comprobante['codigoproyecto'];
	$descuentoporcentaje = $comprobante['descuentoporcentaje'];
	$descuentomonto = $comprobante['descuentomonto'];
	$descuentodescripcion = $comprobante['descuentodescripcion'];
	$cantidadpaginas = $comprobante['cantidadpaginas'];
	$listaprecio = $comprobante['listaprecio'];
	$validactacte = $comprobante['validactacte'];
	$montototalii = $comprobante['montototalii'];
	$fechavencimiento2 = $comprobante['fechavencimiento2'];
	$recargovencimiento2 = $comprobante['recargovencimiento2'];
	$fechavencimiento3 = $comprobante['fechavencimiento3'];
	$recargovencimiento3 = $comprobante['recargovencimiento3'];
	$codigodeposito = $comprobante['codigodeposito'];
	$id_base = $comprobante['id_base'];
	$redi = '';
	preg_match_all( "/\[.*?\](\_\_[0-9]{1,99})?/", $comentarios, $referencias );
	$array_referencias = array();
	foreach($referencias[0] as $v){
		if( strpos($v, "]__") === false ){
			preg_match( "/[0-9\-\_]{1,99}/", $v, $ref );
			$array_referencias[] = $ref[0];
		}else{
			preg_match( "/[0-9\-\_\]]{1,99}/", $v, $ref );
			$array_referencias[] = str_replace( "]__", '__', $ref[0] );
		}
	}

	$redi = implode(' ', $array_referencias);

	$sucursal = intval($codigodeposito);
	if( $sucursal != 1 && $sucursal != 3 ){
		$sucursal = 9;//error
	}
		
	$sql="SELECT `id_cabeza_comprobante` FROM `cabeza_comprobantes` WHERE `tipocomprobante` = '$tipocomprobante' AND `numerocomprobante` = '$numerocomprobante' AND `id_base` = '$id_base' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `cabeza_comprobantes` SET
		`codigocliente` = '$codigocliente',
		`fechacomprobante` = '$fechacomprobante',
		`razonsocial` = '$razonsocial',
		`direccion` = '$direccion',
		`porciva1` = '$porciva1',
		`porciva2` = '$porciva2',
		`iva1` = '$iva1',
		`iva2` = '$iva2',
		`total` = '$total',
		`pagado` = '$pagado',
		`cuentacorriente` = '$cuentacorriente',
		`hora` = '$hora',
		`codigousuario` = '$codigousuario',
		`tipoiva` = '$tipoiva',
		`remitofacturado` = '$remitofacturado',
		`comentarios` = '$comentarios',
		`telefono` = '$telefono',
		`fechavencimiento` = '$fechavencimiento',
		`imprime` = '$imprime',
		`anulada` = '$anulada',
		`cuit` = '$cuit',
		`compra` = '$compra',
		`codigotransporte` = '$codigotransporte',
		`montotransporte` = '$montotransporte',
		`codigomultiplazo` = '$codigomultiplazo',
		`exento` = '$exento',
		`clasecomprobante` = '$clasecomprobante',
		`codigousuario2` = '$codigousuario2',
		`coeficienteiva` = '$coeficienteiva',
		`fechamodificacion` = '$fechamodificacion',
		`desccomprobante` = '$desccomprobante',
		`codigomoneda` = '$codigomoneda',
		`cotizacion` = '$cotizacion',
		`numerotransaccion` = '$numerotransaccion',
		`cantidadbultos` = '$cantidadbultos',
		`nropuntodeventa` = '$nropuntodeventa',
		`codigoproyecto` = '$codigoproyecto',
		`descuentoporcentaje` = '$descuentoporcentaje',
		`descuentomonto` = '$descuentomonto',
		`descuentodescripcion` = '$descuentodescripcion',
		`cantidadpaginas` = '$cantidadpaginas',
		`listaprecio` = '$listaprecio',
		`validactacte` = '$validactacte',
		`montototalii` = '$montototalii',
		`fechavencimiento2` = '$fechavencimiento2',
		`recargovencimiento2` = '$recargovencimiento2',
		`fechavencimiento3` = '$fechavencimiento3',
		`recargovencimiento3` = '$recargovencimiento3',
		`redi` = '$redi',
		`sucursal` = $sucursal
		WHERE `tipocomprobante` = '$tipocomprobante' AND `numerocomprobante` = '$numerocomprobante' AND `id_base` = '$id_base';";
		//echo "<p>$sql</p>";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `cabeza_comprobantes` SET
		`tipocomprobante` = '$tipocomprobante',
		`numerocomprobante` = '$numerocomprobante',
		`codigocliente` = '$codigocliente',
		`fechacomprobante` = '$fechacomprobante',
		`razonsocial` = '$razonsocial',
		`direccion` = '$direccion',
		`porciva1` = '$porciva1',
		`porciva2` = '$porciva2',
		`iva1` = '$iva1',
		`iva2` = '$iva2',
		`total` = '$total',
		`pagado` = '$pagado',
		`cuentacorriente` = '$cuentacorriente',
		`hora` = '$hora',
		`codigousuario` = '$codigousuario',
		`tipoiva` = '$tipoiva',
		`remitofacturado` = '$remitofacturado',
		`comentarios` = '$comentarios',
		`telefono` = '$telefono',
		`fechavencimiento` = '$fechavencimiento',
		`imprime` = '$imprime',
		`anulada` = '$anulada',
		`cuit` = '$cuit',
		`compra` = '$compra',
		`codigotransporte` = '$codigotransporte',
		`montotransporte` = '$montotransporte',
		`codigomultiplazo` = '$codigomultiplazo',
		`exento` = '$exento',
		`clasecomprobante` = '$clasecomprobante',
		`codigousuario2` = '$codigousuario2',
		`coeficienteiva` = '$coeficienteiva',
		`fechamodificacion` = '$fechamodificacion',
		`desccomprobante` = '$desccomprobante',
		`codigomoneda` = '$codigomoneda',
		`cotizacion` = '$cotizacion',
		`numerotransaccion` = '$numerotransaccion',
		`cantidadbultos` = '$cantidadbultos',
		`nropuntodeventa` = '$nropuntodeventa',
		`codigoproyecto` = '$codigoproyecto',
		`descuentoporcentaje` = '$descuentoporcentaje',
		`descuentomonto` = '$descuentomonto',
		`descuentodescripcion` = '$descuentodescripcion',
		`cantidadpaginas` = '$cantidadpaginas',
		`listaprecio` = '$listaprecio',
		`validactacte` = '$validactacte',
		`montototalii` = '$montototalii',
		`fechavencimiento2` = '$fechavencimiento2',
		`recargovencimiento2` = '$recargovencimiento2',
		`fechavencimiento3` = '$fechavencimiento3',
		`recargovencimiento3` = '$recargovencimiento3',
		`id_base` = '$id_base',
		`redi` = '$redi',
		`sucursal` = $sucursal
		;";
//		echo "<p>$sql</p>";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	//echo "<p>$sql</p>";
	return false;
}

function actualizar_cuerpo_comprobante($comprobante){
	global $db_mysql;
	$tipocomprobante = $comprobante['tipocomprobante'];
	$numerocomprobante = $comprobante['numerocomprobante'];
	$linea = $comprobante['linea'];
	$codigoarticulo = $comprobante['codigoarticulo'];
	$descripcion = $comprobante['descripcion'];
	$cantidad = $comprobante['cantidad'];
	$descuento = $comprobante['descuento'];
	$preciounitario = $comprobante['preciounitario'];
	$preciototal = $comprobante['preciototal'];
	$garantia = $comprobante['garantia'];
	$interes = $comprobante['interes'];
	$cantidadremitida = $comprobante['cantidadremitida'];
	$lote = $comprobante['lote'];
	$esconjunto = $comprobante['esconjunto'];
	$fechamodificacion = $comprobante['fechamodificacion'];
	$codigodeposito = $comprobante['codigodeposito'];
	$costoventa = $comprobante['costoventa'];
	$numerotransaccion = $comprobante['numerotransaccion'];
	$codigoparticular = $comprobante['codigoparticular'];
	$porcentajeiva = $comprobante['porcentajeiva'];
	$descdescuento = $comprobante['descdescuento'];
	$tipoprecio = $comprobante['tipoprecio'];
	$porcentajedescuentos = $comprobante['porcentajedescuentos'];
	$montoii = $comprobante['montoii'];
	$coeficienteconversion = $comprobante['coeficienteconversion'];
	$codigoempaque = $comprobante['codigoempaque'];
	$descripcionempaque = $comprobante['descripcionempaque'];
	$observaciones = $comprobante['observaciones'];
	$id_base = $comprobante['id_base'];
	$sql="SELECT `id_cuerpo_comprobante` FROM `cuerpo_comprobantes` WHERE `tipocomprobante` = '$tipocomprobante' AND `numerocomprobante` = '$numerocomprobante' AND `linea` = '$linea' AND `id_base` = '$id_base' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `cuerpo_comprobantes` SET
		`codigoarticulo` = '$codigoarticulo',
		`descripcion` = '$descripcion',
		`cantidad` = '$cantidad',
		`descuento` = '$descuento',
		`preciounitario` = '$preciounitario',
		`preciototal` = '$preciototal',
		`garantia` = '$garantia',
		`interes` = '$interes',
		`cantidadremitida` = '$cantidadremitida',
		`lote` = '$lote',
		`esconjunto` = '$esconjunto',
		`fechamodificacion` = '$fechamodificacion',
		`codigodeposito` = '$codigodeposito',
		`costoventa` = '$costoventa',
		`numerotransaccion` = '$numerotransaccion',
		`codigoparticular` = '$codigoparticular',
		`porcentajeiva` = '$porcentajeiva',
		`descdescuento` = '$descdescuento',
		`tipoprecio` = '$tipoprecio',
		`porcentajedescuentos` = '$porcentajedescuentos',
		`montoii` = '$montoii',
		`coeficienteconversion` = '$coeficienteconversion',
		`codigoempaque` = '$codigoempaque',
		`descripcionempaque` = '$descripcionempaque',
		`observaciones` = '$observaciones'
		WHERE `tipocomprobante` = '$tipocomprobante' AND `numerocomprobante` = '$numerocomprobante' AND `linea` = '$linea' AND `id_base` = '$id_base';";
//		echo "<p>$sql</p>";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `cuerpo_comprobantes` SET
		`tipocomprobante` = '$tipocomprobante',
		`numerocomprobante` = '$numerocomprobante',
		`linea` = '$linea',
		`codigoarticulo` = '$codigoarticulo',
		`descripcion` = '$descripcion',
		`cantidad` = '$cantidad',
		`descuento` = '$descuento',
		`preciounitario` = '$preciounitario',
		`preciototal` = '$preciototal',
		`garantia` = '$garantia',
		`interes` = '$interes',
		`cantidadremitida` = '$cantidadremitida',
		`lote` = '$lote',
		`esconjunto` = '$esconjunto',
		`fechamodificacion` = '$fechamodificacion',
		`codigodeposito` = '$codigodeposito',
		`costoventa` = '$costoventa',
		`numerotransaccion` = '$numerotransaccion',
		`codigoparticular` = '$codigoparticular',
		`porcentajeiva` = '$porcentajeiva',
		`descdescuento` = '$descdescuento',
		`tipoprecio` = '$tipoprecio',
		`porcentajedescuentos` = '$porcentajedescuentos',
		`montoii` = '$montoii',
		`coeficienteconversion` = '$coeficienteconversion',
		`codigoempaque` = '$codigoempaque',
		`descripcionempaque` = '$descripcionempaque',
		`observaciones` = '$observaciones',
		`id_base` = '$id_base';";
//		echo "<p>$sql</p>";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}

function actualizar_cae_afip($cae_afip){
	global $db_mysql;
	$numerotransaccionafip = $cae_afip['numerotransaccionafip'];
	$tipocomprobante = $cae_afip['tipocomprobante'];
	$numerocomprobante = $cae_afip['numerocomprobante'];
	$cae = $cae_afip['cae'];
	$vencimientocae = $cae_afip['vencimientocae'];
	$fechahorasolicitud = $cae_afip['fechahorasolicitud'];
	$respuestaafip = $cae_afip['respuestaafip'];
	$imptotal = $cae_afip['imptotal'];
	$impneto = $cae_afip['impneto'];
	$impopex = $cae_afip['impopex'];
	$impiva = $cae_afip['impiva'];
	$imptrib = $cae_afip['imptrib'];
	$informado = $cae_afip['informado'];

	$sql="SELECT `numerotransaccionafip` FROM `caeafip` WHERE `tipocomprobante` = '$tipocomprobante' AND `numerocomprobante` = '$numerocomprobante' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `caeafip` SET
		`numerotransaccionafip` = '$numerotransaccionafip',
		`tipocomprobante` = '$tipocomprobante',
		`numerocomprobante` = '$numerocomprobante',
		`cae` = '$cae',
		`vencimientocae` = '$vencimientocae',
		`fechahorasolicitud` = '$fechahorasolicitud',
		`respuestaafip` = '$respuestaafip',
		`imptotal` = '$imptotal',
		`impneto` = '$impneto',
		`impopex` = '$impopex',
		`impiva` = '$impiva',
		`imptrib` = '$imptrib',
		`informado` = '$informado'
		WHERE `tipocomprobante` = '$tipocomprobante' AND `numerocomprobante` = '$numerocomprobante';";
//		echo "<p>$sql</p>";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `caeafip` SET
		`numerotransaccionafip` = '$numerotransaccionafip',
		`tipocomprobante` = '$tipocomprobante',
		`numerocomprobante` = '$numerocomprobante',
		`cae` = '$cae',
		`vencimientocae` = '$vencimientocae',
		`fechahorasolicitud` = '$fechahorasolicitud',
		`respuestaafip` = '$respuestaafip',
		`imptotal` = '$imptotal',
		`impneto` = '$impneto',
		`impopex` = '$impopex',
		`impiva` = '$impiva',
		`imptrib` = '$imptrib',
		`informado` = '$informado';";
//		echo "<p>$sql</p>";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}

function borrar_cae_afip($meses){
	global $db_mysql;
	$sql="DELETE FROM `caeafip` WHERE `fechahorasolicitud` >= (NOW() - INTERVAL '$meses' MONTH);";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
//	echo "<p>$sql</p>";
	return false;
}

function borrar_stock_mes($ano,$mes){
	global $db_mysql;
	$sql="DELETE FROM `stock` WHERE `ano` = '$ano' AND `mes` = '$mes';";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}
function borrar_deudas_clientes_principal_mes($ano,$mes){
	global $db_mysql;
	$sql="DELETE FROM `deudas_clientes` WHERE `id_base` = '1' AND `ano` = '$ano' AND `mes` = '$mes';";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}
function borrar_deudas_clientes_ds_mes($ano,$mes){
	global $db_mysql;
	$sql="DELETE FROM `deudas_clientes` WHERE `id_base` = '2' AND `ano` = '$ano' AND `mes` = '$mes';";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}
function borrar_deudas_proveedores_principal_mes($ano,$mes){
	global $db_mysql;
	$sql="DELETE FROM `deudas_proveedores` WHERE `id_base` = '1' AND `ano` = '$ano' AND `mes` = '$mes';";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}
function borrar_deudas_proveedores_ds_mes($ano,$mes){
	global $db_mysql;
	$sql="DELETE FROM `deudas_proveedores` WHERE `id_base` = '2' AND `ano` = '$ano' AND `mes` = '$mes';";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function borrar_asociaciones_clientes(){
	global $db_mysql;
	$sql="DELETE FROM `asociaciones_clientes` WHERE 1;";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function borrar_venta_si_existe($venta){
	global $db_mysql;
	$id_base = $venta['id_base'];
	$tipo_comprobante = $venta['tipo_comprobante'];
	$numero_comprobante = $venta['numero_comprobante'];
	$linea = $venta['linea'];
	$sql="SELECT `id_venta` FROM `ventas` WHERE `id_base` = '$id_base' AND `tipo_comprobante` = '$tipo_comprobante' AND `numero_comprobante` = '$numero_comprobante' AND `linea` = '$linea' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="DELETE FROM `ventas` WHERE `id_base` = '$id_base' AND `tipo_comprobante` = '$tipo_comprobante' AND `numero_comprobante` = '$numero_comprobante' AND `linea` = '$linea';";
		if(!mysqli_query($db_mysql,$sql)){
			return false;
		}
	}
	@mysqli_free_result($result);
	return true;
}

function borrar_redi(){
	global $db_mysql;
	$sql="TRUNCATE `redi`;";
//	echo "<p>$sql</p>";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}else{
		return false;
	}
}

function borrar_remitos_e(){
	global $db_mysql;
	$sql="TRUNCATE `RemitosE`;";
//	echo "<p>$sql</p>";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}else{
		return false;
	}
}

function borrar_remitos_e_det(){
	global $db_mysql;
	$sql="TRUNCATE `RemitosEDet`;";
//	echo "<p>$sql</p>";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}else{
		return false;
	}
}

function borrar_cabezas_comprobantes($meses){
	global $db_mysql;
	$meses = intval($meses);
	$sql="DELETE FROM `cabeza_comprobantes` WHERE `fechamodificacion` >= (NOW() - INTERVAL '$meses' MONTH);";
	echo "<p>$sql</p>";
	if(mysqli_query($db_mysql,$sql)){
		$c = mysqli_affected_rows($db_mysql);
		echo "<p>$c<br />---------------------------</p>";
	}else{
		echo "<p>ERROR<br />---------------------------</p>";
	}
	return true;
}

function borrar_cuerpos_comprobantes($meses){
	global $db_mysql;
	$meses = intval($meses);
	$sql="DELETE FROM `cuerpo_comprobantes` WHERE `fechamodificacion` >= (NOW() - INTERVAL '$meses' MONTH);";
	echo "<p>$sql</p>";
	if(mysqli_query($db_mysql,$sql)){
		$c = mysqli_affected_rows($db_mysql);
		echo "<p>$c<br />---------------------------</p>";
	}else{
		echo "<p>ERROR<br />---------------------------</p>";
	}
	return true;
}

function borrar_ventas($meses){
	global $db_mysql;
	$meses = intval($meses);
	$sql="DELETE FROM `ventas` WHERE `fecha_mod` >= (NOW() - INTERVAL '$meses' MONTH);";
	echo "<p>$sql</p>";
	if(mysqli_query($db_mysql,$sql)){
		$c = mysqli_affected_rows($db_mysql);
		echo "<p>$c<br />---------------------------</p>";
	}else{
		echo "<p>ERROR<br />---------------------------</p>";
	}
	return true;
}

function actualizar_venta($venta){
	global $db_mysql;
	if(borrar_venta_si_existe($venta)){
		$id_tipo_venta = $venta['id_tipo_venta'];
		$id_base = $venta['id_base'];
		$linea = $venta['linea'];
		$anulada = $venta['anulada'];
		$id_sucursal = $venta['id_sucursal'];
		$tipo_comprobante = $venta['tipo_comprobante'];
		$numero_comprobante = $venta['numero_comprobante'];
		$razon_social = $venta['razon_social'];
		$id_ciudad_provincia = strval($venta['id_ciudad']) . strval($venta['id_provincia']);///// parche para no tener problemas con los ids de ciudad repetidos
		$id_provincia = $venta['id_provincia'];
		$id_zona = $venta['id_zona'];
		$id_actividad = $venta['id_actividad'];
		$id_cliente = $venta['id_cliente'];
		$codigo_vendedor = $venta['codigo_vendedor'];
		$id_articulo = $venta['id_articulo'];
		$articulo = str_replace("'","\'",$venta['articulo']);
		$codigo_particular = $venta['codigoparticular'];
		$id_rubro = $venta['id_rubro'];
		$id_super_rubro = $venta['id_super_rubro'];
		$id_grupo_super_rubro = $venta['id_grupo_super_rubro'];
		$id_marca = $venta['id_marca'];
		$cantidad = $venta['cantidad'];
		$porcentaje_descuento_cliente = $venta['porcentaje_descuento_cliente'] * (-1);// es un numero negativo, lo transformo a positivo
		$porcentaje_descuento_marca = $venta['porcentaje_descuento_marca'];// es un numero positivo
		$porcentaje_descuento_gm = $venta['porcentaje_descuento_gm'];// es un numero positivo
		$multiplicador_descuento_cliente = (100-$porcentaje_descuento_cliente)/100;
		$multiplicador_descuento_marca = (100-$porcentaje_descuento_marca)/100;
		$porcentaje_iva = $venta['porcentaje_iva'];// es un numero positivo
		$monto_iva = $venta['monto_iva'];
		if( $tipo_comprobante == 'FB' || $tipo_comprobante == 'NCB' ){
			$valor_unidad = $venta['valor_unidad'] / (($porcentaje_iva/100)+1);
		}else{
			$valor_unidad = $venta['valor_unidad'];
		}
		$costo_venta = $venta['costo_venta'];
		$costo_unidad = $venta['costo_unidad'];
		$valor_total = $cantidad * $valor_unidad;
		if( $id_tipo_venta == 2 ){ // si es gm no aplico los descuentos normales // se quitó por la modificacion de calculo en gm
			$monto_total = $valor_total;
		}else{
			$monto_total = $valor_total * $multiplicador_descuento_cliente * $multiplicador_descuento_marca;
		}
		if( $id_marca == '047'){//parche SOLMI
			$cantidad = ( $cantidad / 50 );
			$valor_unidad = ( $valor_unidad * 50 );
			$costo_unidad = ( $costo_unidad * 50 );
		}
		$fecha = $venta['fecha'];
		$fecha_mod = $venta['fecha_mod'];
		$ano = $venta['ano'];
		$mes = $venta['mes'];
		$dia = $venta['dia'];
		$trimestre = trimestre_mes($mes);

		$costo_total = $costo_venta;
		//parche para poner a cero el monto de ventas a clientes que tienen en la razon social %(GM)% => se cambió la palabra gm por excel
		if( $id_base == 2 && validador('(excel)',strtolower($razon_social)) ){
			$costo_venta = 0;
			$costo_total = 0;
			$monto_total = 0;
		}
		if( $id_base == 2 && validador('(gm)',strtolower($razon_social)) ){
			$costo_venta = 0;
			$costo_total = 0;
			$monto_total = 0;
		}
		if( $id_base == 2 && validador('(gm2)',strtolower($razon_social)) ){
			$costo_venta = 0;
			$costo_total = 0;
			$monto_total = 0;
		}
		//parche para comisiones ag
		if( $id_base == 2 && validador('^nc',strtolower($tipo_comprobante)) && ( validador('cuenta y orden',strtolower($articulo)) || validador('cta y orden',strtolower($articulo)) ) ){
			$costo_venta = $monto_total * floatval(0.2);
			$costo_total = $monto_total * floatval(0.2);
			$monto_total = 0;
		}
		//parche para facturas de fric rot en gm
		if( $id_cliente == '00403' ){
			$costo_venta = 0;
			$costo_total = 0;
			$monto_total = 0;
		}

		//parche para comisiones ag
		if( $id_base == 2 && validador('^nc',strtolower($tipo_comprobante)) && ( validador('cuenta y orden',strtolower($articulo)) || validador('cta y orden',strtolower($articulo)) ) ){
			$costo_venta = $monto_total * floatval(0.2);
			$costo_total = $monto_total * floatval(0.2);
			$monto_total = 0;
		}

		//parche para anular las compensaciones manuales que se hacen para flexxus
		if( validador('^n',strtolower($tipo_comprobante)) && validador('compensacion flexxus',strtolower($articulo)) ){
			$anulada = 1;
		}
		if( validador('^n',strtolower($tipo_comprobante)) && validador('compensacion de flexxus',strtolower($articulo)) ){
			$anulada = 1;
		}
		if( validador('^n',strtolower($tipo_comprobante)) && validador('nc por finalizacion excel',strtolower($articulo)) ){
			$anulada = 1;
		}

		//otro parche mas
		if( 
			validador('^nc',strtolower($tipo_comprobante)) &&
			( 
				validador('^NC POR ARTICULOS AG',strtoupper($articulo)) ||
				validador('^NC ARTICULO AG',strtoupper($articulo)) ||
				validador('^NC ARTICULOS AG',strtoupper($articulo)) ||
				validador('^NC ARTICULOS DE AG',strtoupper($articulo)) ||
				validador('^NC ARTICULOS CUENTA Y ORDEN AG',strtoupper($articulo)) ||
				validador('^NC ARTICULO CUENTA Y ORDEN AG',strtoupper($articulo))
			)
		){
			$monto_total = 0;
		}

		$op_especial = 0;
		if( 
			$codigo_particular == 'OPESPECIALDS' || 
			$codigo_particular == 'OPESPECIAL-AG-CYO' || 
			$codigo_particular == 'OPESPECIAL-SKF' || 
			$codigo_particular == 'OPESPECIAL-THO' 
		){
			$op_especial = 1;
			//$porcentaje_descuento_cliente = $venta['descuento_op_especial'];
			//$porcentaje_descuento_gm = $venta['descuento_op_especial'];
		}elseif( $codigo_particular == 'OPESPECIAL' ){ // op especial fric rot
			$op_especial = 2;
			$porcentaje_descuento_cliente = $venta['descuento_op_especial'];
			$porcentaje_descuento_gm = $venta['descuento_op_especial'];
		}
		$vta_congelada = 0;
		if( $tipo_comprobante == 'RE' && $id_base == 2 ){
			$vta_congelada = 1;
		}

		$sql = "INSERT INTO `ventas` SET
		`id_tipo_venta` = '$id_tipo_venta',
		`op_especial` = '$op_especial',
		`vta_congelada` = '$vta_congelada',
		`id_base` = '$id_base',
		`id_ciudad_provincia` = '$id_ciudad_provincia',
		`id_provincia` = '$id_provincia',
		`id_zona` = '$id_zona',
		`id_actividad` = '$id_actividad',
		`id_cliente` = '$id_cliente',
		`codigo_vendedor` = '$codigo_vendedor',
		`id_marca` = '$id_marca',
		`id_sucursal` = '$id_sucursal',
		`tipo_comprobante` = '$tipo_comprobante',
		`numero_comprobante` = '$numero_comprobante',
		`linea` = '$linea',
		`anulada` = '$anulada',
		`id_articulo` = '$id_articulo',
		`articulo` = '$articulo',
		`codigo_particular` = '$codigo_particular',
		`id_rubro` = '$id_rubro',
		`id_super_rubro` = '$id_super_rubro',
		`id_grupo_super_rubro` = '$id_grupo_super_rubro',
		`cantidad` = '$cantidad',
		`valor_unidad` = '$valor_unidad',
		`costo_venta` = '$costo_venta',
		`costo_unidad` = '$costo_unidad',
		`porcentaje_iva` = '$porcentaje_iva',
		`porcentaje_descuento_cliente` = '$porcentaje_descuento_cliente',
		`porcentaje_descuento_marca` = '$porcentaje_descuento_marca',
		`porcentaje_descuento_gm` = '$porcentaje_descuento_gm',
		`monto_iva` = '$monto_iva',
		`monto_total` = '$monto_total',
		`costo_total` = '$costo_total',
		`fecha` = '$fecha',
		`fecha_mod` = '$fecha_mod',
		`ano` = '$ano',
		`mes` = '$mes',
		`dia` = '$dia',
		`trimestre` = '$trimestre';";
		if( $id_tipo_venta == 1 && $tipo_comprobante == 'RE' ){ // parche para sacar los re de ventas, salvo que sean gm
			return true;
		}elseif( $id_cliente == '01065' || $id_cliente == '03849' ){ // parche para sacar las ventas de los clientes distrisuper pico y distrisuper mdp
			return true;
		}else{
			if(mysqli_query($db_mysql,$sql)){
				return true;
			}else{
				echo '<br /><br />' . $sql;
				return false;
			}
		}
	}else{
		return false;
	}
}
function actualizar_zona($zona){
	global $db_mysql;
	$id_zona = $zona['id_zona'];
	$nombre = strval($zona['nombre']);
	$fecha_mod = strval($zona['fecha_mod']);
	$sql="SELECT `id_zona` FROM `zonas` WHERE `id_zona` = '$id_zona' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `zonas` SET `nombre_zona` = '$nombre', `fecha_mod` = '$fecha_mod' WHERE `id_zona` = '$id_zona';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `zonas` SET `id_zona` = '$id_zona', `nombre_zona` = '$nombre', `fecha_mod` = '$fecha_mod';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}
function actualizar_actividad($actividad){
	global $db_mysql;
	$id_actividad = $actividad['id_actividad'];
	$nombre = strval($actividad['nombre']);
	$fecha_mod = strval($actividad['fecha_mod']);
	$sql="SELECT `id_actividad` FROM `actividad` WHERE `id_actividad` = '$id_actividad' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$sql="UPDATE `actividad` SET `nombre_actividad` = '$nombre', `fecha_mod` = '$fecha_mod' WHERE `id_actividad` = '$id_actividad';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `actividad` SET `id_actividad` = '$id_actividad', `nombre_actividad` = '$nombre', `fecha_mod` = '$fecha_mod';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}


/******* Funciones DB Firebird *********/

/******* importar desde ultima fecha *********/

function importar_articulos(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_articulos();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "select first $limite_a_importar
	ARTICULOS.CODIGOARTICULO,
	ARTICULOS.ACTIVO,
	ARTICULOS.DESCRIPCION,
	ARTICULOS.CODIGORUBRO,
	ARTICULOS.CODIGOMARCA,
	ARTICULOSPROVEEDOR.CODIGOPROVEEDOR,
	ARTICULOS.CODIGOPARTICULAR,
	ARTICULOS.CODIGOGRUPOALTERNATIVO,
	ARTICULOS.FECHAMODIFICACION,
	ARTICULOS.FECHAALTA,
	ARTICULOS.PRECIOVENTA1
	from ARTICULOS left join ARTICULOSPROVEEDOR ON ARTICULOS.CODIGOARTICULO = ARTICULOSPROVEEDOR.CODIGOARTICULO
	WHERE ARTICULOS.FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	ORDER BY ARTICULOS.DESCRIPCION asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['id_articulo'] = strval($fb->CODIGOARTICULO);
		$data[$i]['activo'] = strval($fb->ACTIVO);
		$data[$i]['nombre'] = strval(str_replace("'","\'",mb_convert_encoding($fb->DESCRIPCION, "UTF-8")));
		$data[$i]['id_rubro'] = strval($fb->CODIGORUBRO);
		$data[$i]['id_marca'] = strval($fb->CODIGOMARCA);
		$data[$i]['codigo_proveedor'] = strval($fb->CODIGOPROVEEDOR);
		$data[$i]['codigo_particular'] = strval(str_replace("'","\'",mb_convert_encoding($fb->CODIGOPARTICULAR, "UTF-8")));
		$data[$i]['codigo_grupoalternativo'] = strval(str_replace("'","\'",mb_convert_encoding($fb->CODIGOGRUPOALTERNATIVO, "UTF-8")));
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$data[$i]['fecha_alta'] = strval($fb->FECHAALTA);
		$data[$i]['precio_lista'] = floatval($fb->PRECIOVENTA1);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_lotes(){
	global $db_principal;
	global $limite_a_importar;
	$limite_a_importar = $limite_a_importar * 10;
	$data = array();
	$sql = "select first $limite_a_importar CODIGO, CODIGOCAMPO, VALOR from CAMPOSDINAMICOSTABLAS WHERE CODIGOCAMPO = '35' OR CODIGOCAMPO = '36';";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['codigo'] = strval($fb->CODIGO);
		$data[$i]['codigocampo'] = strval($fb->CODIGOCAMPO);
		$data[$i]['valor'] = strval($fb->VALOR);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_stock(){
	global $db_principal;
	global $limite_a_importar;

//	$descuento_proveedor = importar_descuentos_proveedor();

	$data = array();
	$sql = "select first $limite_a_importar CASILLEROS.CODIGOARTICULO, 
	ARTICULOS.CODIGOMARCA, 
	ARTICULOSPROVEEDOR.CODIGOPROVEEDOR, 
	CASILLEROS.CODIGODEPOSITO, 
	CASILLEROS.STOCKACTUAL, 
	ARTICULOSPROVEEDOR.PRECIO, 
	ARTICULOSPROVEEDOR.BONIFICACIONP
	from CASILLEROS join ARTICULOS ON ARTICULOS.CODIGOARTICULO = CASILLEROS.CODIGOARTICULO
	join ARTICULOSPROVEEDOR ON ARTICULOS.CODIGOARTICULO = ARTICULOSPROVEEDOR.CODIGOARTICULO
	WHERE 
	CASILLEROS.STOCKACTUAL != 0 AND 
	ARTICULOS.ACTIVO = 1 AND 
	ARTICULOSPROVEEDOR.ACTIVO = '1' AND 
	ARTICULOSPROVEEDOR.PRECIO != 0;"; // AND ARTICULOSPROVEEDOR.ACTIVO = 1
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['id_articulo'] = strval($fb->CODIGOARTICULO);
		$data[$i]['id_marca'] = strval($fb->CODIGOMARCA);
		$data[$i]['codigodeposito'] = strval(intval($fb->CODIGODEPOSITO));
		if( $data[$i]['codigodeposito'] == '2' ) $data[$i]['codigodeposito'] = '1';// parche deposito secundario pico
		$data[$i]['unidades'] = strval($fb->STOCKACTUAL);
		$data[$i]['monto'] = floatval($fb->PRECIO) * floatval( 1 - floatval($fb->BONIFICACIONP/100) );
/*
		if( isset($descuento_proveedor[strval($fb->CODIGOPROVEEDOR)]) ){
			$data[$i]['monto'] = floatval($fb->PRECIO) * $descuento_proveedor[strval($fb->CODIGOPROVEEDOR)];
		}else{
			$data[$i]['monto'] = floatval($fb->PRECIO);
		}
*/
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_plan_de_cuentas(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$sql = "select first $limite_a_importar * from VISTACONTABLE;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['cuenta'] = strval($fb->CUENTA);
		$data[$i]['descripcion'] = strval(str_replace("'","\'",mb_convert_encoding($fb->DESCRIPCION, "UTF-8")));
		$data[$i]['cuenta_madre'] = strval($fb->CUENTAMADRE);
		$data[$i]['ingresos'] = intval($fb->INGRESOS);
		$data[$i]['egresos'] = intval($fb->EGRESOS);
		$data[$i]['activa'] = intval($fb->ACTIVA);
		$data[$i]['imputable'] = intval($fb->IMPUTABLE);
		$data[$i]['patrimonial'] = intval($fb->PATRIMONIAL);
		$data[$i]['utilizacioncentrocosto'] = intval($fb->UTILIZACENTROSCOSTO);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_cobranzas(){
	global $limite_a_importar;
	$data = array();
	$lineas = file('http://www.saasnet.com.ar/totalrecibos.csv');
	$null = array_shift($lineas);
	$i=0;
	foreach($lineas as $linea){
		$array_linea = explode(',',$linea);
		$data[$i]['ano'] = intval($array_linea[0]);
		$data[$i]['mes'] = intval($array_linea[1]);
		$data[$i]['vendedor'] = strval($array_linea[2]);
		$data[$i]['total'] = floatval($array_linea[3]);
		$data[$i]['descuento'] = floatval($array_linea[4]);
		$data[$i]['dias'] = floatval($array_linea[5]);
		$data[$i]['rentabilidad_porc'] = floatval($array_linea[6]);
		$data[$i]['rentabilidad_total'] = floatval($array_linea[7]);
		$i++;
	}
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_codigos_barra(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_codigos_barra();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "select first $limite_a_importar
	CODIGOBARRA, CODIGOARTICULO, FECHAMODIFICACION
	from CODIGOSBARRA
	WHERE FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	ORDER BY CODIGOBARRA asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['codigo_barra'] = strval($fb->CODIGOBARRA);
		$data[$i]['id_articulo'] = strval($fb->CODIGOARTICULO);
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_deudas_clientes_principal(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$timestamp_hoy = strtotime(date("Y-m-d"));
	$sql = "select 
	cabeza.TIPOCOMPROBANTE as TIPOCOMPROBANTE,
	cabeza.NUMEROCOMPROBANTE as NUMEROCOMPROBANTE,
	cabeza.CODIGOCLIENTE as CODIGOCLIENTE,
	cabeza.CODIGOUSUARIO as CODIGOUSUARIO,
	cabeza.IVA1 as IVA1,
	cabeza.TOTAL as TOTAL,
	cabeza.PAGADO as PAGADO,
	cabeza.FECHACOMPROBANTE as FECHA,
	cuerpo.CODIGODEPOSITO as CODIGODEPOSITO
	from CABEZACOMPROBANTES as cabeza
	join CUERPOCOMPROBANTES as cuerpo
	ON (cabeza.NUMEROCOMPROBANTE = cuerpo.NUMEROCOMPROBANTE AND cabeza.TIPOCOMPROBANTE = cuerpo.TIPOCOMPROBANTE AND cuerpo.LINEA = 1)
	WHERE
	cabeza.TIPOCOMPROBANTE != 'RE' AND cabeza.TIPOCOMPROBANTE != 'RI' AND cabeza.TIPOCOMPROBANTE != 'INA'
	AND cabeza.ANULADA = 0 AND cabeza.TOTAL != 0 AND ROUND((cabeza.TOTAL+cabeza.IVA1)-cabeza.PAGADO) != 0;";
	//(cabeza.TIPOCOMPROBANTE = 'FA' OR cabeza.TIPOCOMPROBANTE = 'FB' OR cabeza.TIPOCOMPROBANTE = 'NCA' OR cabeza.TIPOCOMPROBANTE = 'NCB' OR cabeza.TIPOCOMPROBANTE = 'SIV')
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['tipo_comprobante'] = strval($fb->TIPOCOMPROBANTE);
		$data[$i]['numero_comprobante'] = strval($fb->NUMEROCOMPROBANTE);
		$data[$i]['id_cliente'] = strval($fb->CODIGOCLIENTE);
		$data[$i]['id_base'] = '1';
		$data[$i]['codigo_deposito'] = strval(intval($fb->CODIGODEPOSITO));
		if( $data[$i]['codigo_deposito'] == '2' ) $data[$i]['codigo_deposito'] = '1';// parche deposito secundario pico
		$data[$i]['codigo_vendedor'] = strval(str_replace("'","\'",mb_convert_encoding($fb->CODIGOUSUARIO, "UTF-8")));
		$data[$i]['total'] = floatval($fb->TOTAL) + floatval($fb->IVA1);
		$data[$i]['pagado'] = floatval($fb->PAGADO);
		if( validador('^nc',strtolower(trim($data[$i]['tipo_comprobante']))) ){ // parche para las notas de credito
			$data[$i]['pagado'] = abs($data[$i]['pagado']);
			$data[$i]['deuda'] = $data[$i]['total'] + $data[$i]['pagado'];
		}else{
			$data[$i]['deuda'] = $data[$i]['total'] - $data[$i]['pagado'];
		}
		$timestamp_comprobante = strtotime(date( "Y-m-d", strtotime($fb->FECHA)));
		$data[$i]['dias_deuda'] = floatval( ($timestamp_hoy-$timestamp_comprobante)/86400 );
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}
function importar_deudas_clientes_ds(){
	global $db_ds;
	global $limite_a_importar;
	$data = array();
	$timestamp_hoy = strtotime(date("Y-m-d"));
	$sql = "select 
	cabeza.TIPOCOMPROBANTE as TIPOCOMPROBANTE,
	cabeza.NUMEROCOMPROBANTE as NUMEROCOMPROBANTE,
	cabeza.CODIGOCLIENTE as CODIGOCLIENTE,
	cabeza.CODIGOUSUARIO as CODIGOUSUARIO,
	cabeza.IVA1 as IVA1,
	cabeza.TOTAL as TOTAL,
	cabeza.PAGADO as PAGADO,
	cabeza.FECHACOMPROBANTE as FECHA,
	cuerpo.CODIGODEPOSITO as CODIGODEPOSITO
	from CABEZACOMPROBANTES as cabeza
	join CUERPOCOMPROBANTES as cuerpo
	ON (cabeza.NUMEROCOMPROBANTE = cuerpo.NUMEROCOMPROBANTE AND cabeza.TIPOCOMPROBANTE = cuerpo.TIPOCOMPROBANTE AND cuerpo.LINEA = 1)
	WHERE
	cabeza.TIPOCOMPROBANTE != 'RE' AND cabeza.TIPOCOMPROBANTE != 'RI' AND cabeza.TIPOCOMPROBANTE != 'INA'
	AND cabeza.ANULADA = 0 AND cabeza.TOTAL != 0 AND ROUND((cabeza.TOTAL+cabeza.IVA1)-cabeza.PAGADO) != 0;";
	// (cabeza.TIPOCOMPROBANTE = 'FA' OR cabeza.TIPOCOMPROBANTE = 'FB' OR cabeza.TIPOCOMPROBANTE = 'NCA' OR cabeza.TIPOCOMPROBANTE = 'NCB' OR cabeza.TIPOCOMPROBANTE = 'SIV')
	$res = ibase_query($db_ds, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['tipo_comprobante'] = strval($fb->TIPOCOMPROBANTE);
		$data[$i]['numero_comprobante'] = strval($fb->NUMEROCOMPROBANTE);
		$data[$i]['id_cliente'] = strval($fb->CODIGOCLIENTE);
		$data[$i]['id_base'] = '2';
		$data[$i]['codigo_deposito'] = strval(intval($fb->CODIGODEPOSITO));
		if( $data[$i]['codigo_deposito'] == '2' ) $data[$i]['codigo_deposito'] = '1';// parche deposito secundario pico
		$data[$i]['codigo_vendedor'] = strval(str_replace("'","\'",mb_convert_encoding($fb->CODIGOUSUARIO, "UTF-8")));
		$data[$i]['total'] = floatval($fb->TOTAL) + floatval($fb->IVA1);
		$data[$i]['pagado'] = floatval($fb->PAGADO);
		if( validador('^nc',strtolower(trim($data[$i]['tipo_comprobante']))) ){ // parche para las notas de credito
			$data[$i]['pagado'] = abs($data[$i]['pagado']);
			$data[$i]['deuda'] = $data[$i]['total'] + $data[$i]['pagado'];
		}else{
			$data[$i]['deuda'] = $data[$i]['total'] - $data[$i]['pagado'];
		}
		$timestamp_comprobante = strtotime(date( "Y-m-d", strtotime($fb->FECHA)));
		$data[$i]['dias_deuda'] = floatval( ($timestamp_hoy-$timestamp_comprobante)/86400 );
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_deudas_proveedores_principal(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$sql = "SELECT first $limite_a_importar
	TIPOCOMPROBANTE,
	NUMEROCOMPROBANTE,
	CODIGOPROVEEDOR,
	RAZONSOCIAL,
	CODIGOUSUARIO,
	CODIGOPROVINCIA,
	TOTAL,
	PAGADO,
	(IVA1+TOTAL-PAGADO) as DEUDA
	FROM CABEZACOMPRAS
	WHERE ANULADA = '0'
	AND TIPOCOMPROBANTE != 'RE'
	AND TIPOCOMPROBANTE != 'RI'
	AND TIPOCOMPROBANTE != 'RDC'
	AND RAZONSOCIAL NOT LIKE '%Anulaci%'
	AND TOTAL != 0 AND ROUND(IVA1+TOTAL-PAGADO) != 0
	ORDER BY FECHACOMPROBANTE asc;";
	$i=0;
	$res = ibase_query($db_principal, $sql);
	while($fb = ibase_fetch_object($res)){
		$data[$i]['id_base'] = 1;
		$data[$i]['tipo'] = strval($fb->TIPOCOMPROBANTE);
		$data[$i]['numero'] = strval($fb->NUMEROCOMPROBANTE);
		$data[$i]['codigo_proveedor'] = strval($fb->CODIGOPROVEEDOR);
		$data[$i]['razon_social'] = strval(str_replace("'","\'",mb_convert_encoding($fb->RAZONSOCIAL, "UTF-8")));
		$data[$i]['codigo_vendedor'] = strval($fb->CODIGOUSUARIO);
		$data[$i]['id_provincia'] = strval($fb->CODIGOPROVINCIA);
		$data[$i]['total'] = floatval($fb->TOTAL);
		$data[$i]['pagado'] = floatval($fb->PAGADO);
		$data[$i]['deuda'] = floatval($fb->DEUDA);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
//	echo "<p>rows: $rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}
function importar_deudas_proveedores_ds(){
	global $db_ds;
	global $limite_a_importar;
	$data = array();
	$sql = "SELECT first $limite_a_importar
	TIPOCOMPROBANTE,
	NUMEROCOMPROBANTE,
	CODIGOPROVEEDOR,
	RAZONSOCIAL,
	CODIGOUSUARIO,
	CODIGOPROVINCIA,
	TOTAL,
	PAGADO,
	(IVA1+TOTAL-PAGADO) as DEUDA
	FROM CABEZACOMPRAS
	WHERE ANULADA = '0'
	AND TIPOCOMPROBANTE != 'RE'
	AND TIPOCOMPROBANTE != 'RI'
	AND TIPOCOMPROBANTE != 'RDC'
	AND RAZONSOCIAL NOT LIKE '%Anulaci%'
	AND TOTAL != 0 AND ROUND(IVA1+TOTAL-PAGADO) != 0
	ORDER BY FECHACOMPROBANTE asc;";
	$i=0;
	$res = ibase_query($db_ds, $sql);
	while($fb = ibase_fetch_object($res)){
		$data[$i]['id_base'] = 2;
		$data[$i]['tipo'] = strval($fb->TIPOCOMPROBANTE);
		$data[$i]['numero'] = strval($fb->NUMEROCOMPROBANTE);
		$data[$i]['codigo_proveedor'] = strval($fb->CODIGOPROVEEDOR);
		$data[$i]['razon_social'] = strval(str_replace("'","\'",mb_convert_encoding($fb->RAZONSOCIAL, "UTF-8")));
		$data[$i]['codigo_vendedor'] = strval($fb->CODIGOUSUARIO);
		$data[$i]['id_provincia'] = strval($fb->CODIGOPROVINCIA);
		$data[$i]['total'] = floatval($fb->TOTAL);
		$data[$i]['pagado'] = floatval($fb->PAGADO);
		$data[$i]['deuda'] = floatval($fb->DEUDA);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
//	echo "<p>rows: $rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_asociaciones_clientes(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	borrar_asociaciones_clientes();
	$sql = "select first $limite_a_importar CODIGO, NOMBRE, VALOR from CAMPOSDINAMICOS
	LEFT JOIN CAMPOSDINAMICOSTABLAS ON CAMPOSDINAMICOS.CODIGOCAMPO = CAMPOSDINAMICOSTABLAS.CODIGOCAMPO
	where
	nombre = 'CUENTA OP DISTRISUPER' OR
	nombre = 'CUENTA OP FRIC-ROT' OR
	nombre = 'OP DISTRISUPER FECHA-SALDO INICIAL' OR
	nombre = 'OP FRIC-ROT FECHA-SALDO INICIAL';";
	$res = ibase_query($db_principal, $sql);
	while($fb = ibase_fetch_object($res)){
		$nombre = strval($fb->NOMBRE);
		$codigo = strval($fb->CODIGO);
		$data[$codigo]['codigo'] = $codigo;
		if( $nombre == 'CUENTA OP DISTRISUPER' || $nombre == 'CUENTA OP FRIC-ROT' ){
			$data[$codigo][$nombre] = strval($fb->VALOR);
		}elseif( $nombre == 'OP DISTRISUPER FECHA-SALDO INICIAL'){
			$fecha_saldo = strval($fb->VALOR);
			//echo "<p>----------------------> distri $fecha_saldo</p>";
			$null = explode( '(' , $fecha_saldo );
			$fecha = implode( '-' , array_reverse( explode( '/' , trim( @$null[0] ) ) ) );
			//echo "<p>----------------------> fecha $fecha</p>";
			$monto = floatval( str_replace( ')' , '' , trim( @$null[1] ) ) );
			//echo "<p>----------------------> monto $monto</p>";
			$data[$codigo]['saldo-inicial-distrisuper']['fecha'] = $fecha;
			$data[$codigo]['saldo-inicial-distrisuper']['monto'] = $monto;
		}elseif( $nombre == 'OP FRIC-ROT FECHA-SALDO INICIAL' ){
			$fecha_saldo = strval($fb->VALOR);
			//echo "<p>----------------------> fric $fecha_saldo</p>";
			$null = explode( '(' , $fecha_saldo );
			$fecha = implode( '-' , array_reverse( explode( '/' , trim( @$null[0] ) ) ) );
			//echo "<p>----------------------> fecha $fecha</p>";
			$monto = floatval( str_replace( ')' , '' , trim( @$null[1] ) ) );
			//echo "<p>----------------------> monto $monto</p>";
			$data[$codigo]['saldo-inicial-fric-rot']['fecha'] = $fecha;
			$data[$codigo]['saldo-inicial-fric-rot']['monto'] = $monto;
		}
	}
	ibase_free_result( $res );
	$rows = count($data);
	echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_clientes(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	borrar_clientes();
	$fecha_inicio = ultima_importacion_clientes();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "select first $limite_a_importar 
	CODIGOCLIENTE, 
	CODIGOPARTICULAR, 
	ACTIVO, 
	RAZONSOCIAL, 
	CODIGOLOCALIDAD, 
	CODIGOPROVINCIA, 
	CODIGOZONA, 
	CODIGOACTIVIDAD, 
	CODIGOVENDEDOR, 
	BARRIO, 
	BONIFICACION,
	FECHAMODIFICACION 
	from CLIENTES 
	WHERE FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	ORDER BY RAZONSOCIAL asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['id_cliente'] = strval($fb->CODIGOCLIENTE);
		$data[$i]['codigo_particular'] = strval(str_replace("'","\'",mb_convert_encoding($fb->CODIGOPARTICULAR, "UTF-8")));
		$data[$i]['nombre'] = strval(str_replace("'","\'",mb_convert_encoding($fb->RAZONSOCIAL, "UTF-8")));
		$data[$i]['activo'] = strval($fb->ACTIVO);
		$data[$i]['id_ciudad'] = strval($fb->CODIGOLOCALIDAD);
		$data[$i]['id_provincia'] = strval($fb->CODIGOPROVINCIA);
		$data[$i]['id_zona'] = strval($fb->CODIGOZONA);
		$data[$i]['id_actividad'] = strval($fb->CODIGOACTIVIDAD);
		$data[$i]['codigo_vendedor'] = strval(str_replace("'","\'",mb_convert_encoding($fb->CODIGOVENDEDOR, "UTF-8")));
		$data[$i]['barrio'] = strval(str_replace("'","\'",mb_convert_encoding($fb->BARRIO, "UTF-8")));
		$data[$i]['bonificacion'] = floatval($fb->BONIFICACION);
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}


function importar_ubicaciones(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	borrar_ubicaciones();
	$sql = "select first $limite_a_importar CODIGOCAMPO, CODIGO, VALOR from CAMPOSDINAMICOSTABLAS
	where
	CODIGOCAMPO = '011' OR
	CODIGOCAMPO = '012' OR
	CODIGOCAMPO = '015';";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['codigocampo'] = strval($fb->CODIGOCAMPO);
		$data[$i]['codigo'] = strval(str_replace("'","\'",mb_convert_encoding($fb->CODIGO, "UTF-8")));
		$data[$i]['valor'] = strval(str_replace("'","\'",mb_convert_encoding($fb->VALOR, "UTF-8")));
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function actualizar_ubicacion($ubicacion){
	global $db_mysql;
	$codigocampo = $ubicacion['codigocampo'];
	$codigo = $ubicacion['codigo'];
	$valor = $ubicacion['valor'];
	$sql="INSERT INTO `ubicaciones` SET `codigocampo` = '$codigocampo', `codigo` = '$codigo', `valor` = '$valor';";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function guardar_esp($cuenta){
	global $db_mysql;
	$sql="INSERT INTO `esp` SET `cuenta` = '{$cuenta['cuenta']}';";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function actualizar_regla_esp($regla){
	global $db_mysql;
	$sql="UPDATE `esp` SET
	`grupo` = '{$regla['grupo']}',
	`super_grupo` = '{$regla['super_grupo']}',
	`clase` = '{$regla['clase']}'
	WHERE `cuenta` LIKE '{$regla['cuenta']}%';";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function importar_ciudades(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_ciudades();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "select first $limite_a_importar CODIGOPROVINCIA, CODIGOLOCALIDAD, NOMBRE, FECHAMODIFICACION from LOCALIDADES 
	WHERE FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	ORDER BY NOMBRE asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['id_ciudad'] = strval($fb->CODIGOLOCALIDAD);
		$data[$i]['id_provincia'] = strval($fb->CODIGOPROVINCIA);
		$data[$i]['nombre'] = strval(str_replace("'","\'",mb_convert_encoding($fb->NOMBRE, "UTF-8")));
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}
function importar_grupos_super_rubros(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_grupos_super_rubros();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "select first $limite_a_importar CODIGOGRUPOSUPERRUBRO, DESCRIPCION, FECHAMODIFICACION from GRUPOSUPERRUBROS 
	WHERE FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	ORDER BY DESCRIPCION asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['id_grupo_super_rubro'] = strval($fb->CODIGOGRUPOSUPERRUBRO);
		$data[$i]['nombre'] = strval(str_replace("'","\'",mb_convert_encoding($fb->DESCRIPCION, "UTF-8")));
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}
function importar_marcas(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_marcas();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "select first $limite_a_importar CODIGOMARCA, DESCRIPCION, FECHAMODIFICACION, MUESTRAWEB from MARCAS 
	WHERE FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	ORDER BY DESCRIPCION asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['id_marca'] = strval($fb->CODIGOMARCA);
		$data[$i]['nombre'] = strval(str_replace("'","\'",mb_convert_encoding($fb->DESCRIPCION, "UTF-8")));
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$data[$i]['activa'] = intval($fb->MUESTRAWEB);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}
function importar_provincias(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_provincias();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "select first $limite_a_importar CODIGOPROVINCIA, NOMBRE, FECHAMODIFICACION from PROVINCIAS 
	WHERE FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	ORDER BY NOMBRE asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['id_provincia'] = strval($fb->CODIGOPROVINCIA);
		$data[$i]['nombre'] = strval(str_replace("'","\'",mb_convert_encoding($fb->NOMBRE, "UTF-8")));
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$i++;
	}
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}
function importar_rubros(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_rubros();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "select first $limite_a_importar CODIGORUBRO, CODIGOSUPERRUBRO, DESCRIPCION, FECHAMODIFICACION from RUBROS 
	WHERE FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	ORDER BY DESCRIPCION asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['id_rubro'] = strval($fb->CODIGORUBRO);
		$data[$i]['id_super_rubro'] = strval($fb->CODIGOSUPERRUBRO);
		$data[$i]['nombre'] = strval(str_replace("'","\'",mb_convert_encoding($fb->DESCRIPCION, "UTF-8")));
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}
function importar_sucursales(){ //////// parche, sacar por deposito
	$data['1']['id_sucursal']='1';
	$data['1']['nombre']='CASA CENTRAL';
	$data['3']['id_sucursal']='3';
	$data['3']['nombre']='MDP';
	$data['5']['id_sucursal']='5';
	$data['5']['nombre']='BA';
	return $data;
}
function importar_bases(){ //////// parche, db
	$data['1']['id_base']=1;
	$data['1']['nombre']='Principal';
	$data['2']['id_base']=2;
	$data['2']['nombre']='DS';
	return $data;
}
function importar_tipos_venta(){ //////// parche, gm
	$data['1']['id_tipo_venta']=1;
	$data['1']['nombre']='NO';
	$data['2']['id_tipo_venta']=2;
	$data['2']['nombre']='SI';
	return $data;
}
function importar_super_rubros(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_super_rubros();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "select first $limite_a_importar CODIGOSUPERRUBRO, CODIGOGRUPOSUPERRUBRO, DESCRIPCION, FECHAMODIFICACION from SUPERRUBROS 
	WHERE FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	ORDER BY DESCRIPCION asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['id_super_rubro'] = strval($fb->CODIGOSUPERRUBRO);
		$data[$i]['id_grupo_super_rubro'] = strval($fb->CODIGOGRUPOSUPERRUBRO);
		$data[$i]['nombre'] = strval(str_replace("'","\'",mb_convert_encoding($fb->DESCRIPCION, "UTF-8")));
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}
function importar_vendedores(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_vendedores();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "select first $limite_a_importar CODIGOUSUARIO, ACTIVO, ESVENDEDOR, RAZONSOCIAL, FECHAMODIFICACION from USUARIOS 
	WHERE FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	ORDER BY RAZONSOCIAL asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['codigo_vendedor'] = strval(str_replace("'","\'",mb_convert_encoding($fb->CODIGOUSUARIO, "UTF-8")));
		$data[$i]['activo'] = strval($fb->ACTIVO);
		$data[$i]['es_vendedor'] = strval($fb->ESVENDEDOR);
		$data[$i]['nombre'] = strval(str_replace("'","\'",mb_convert_encoding($fb->RAZONSOCIAL, "UTF-8")));
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function numero_registros_ventas( $fecha, $id_base ){
	global $db_mysql;
	$sql="SELECT count(`id_venta`) as `numero_ventas` FROM `ventas` WHERE `id_base` = '$id_base' AND `fecha_mod` >= '$fecha' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		return $dat['numero_ventas'];
	}
	return false;
}

function borrar_ventas_fecha($fecha, $id_base ){
	global $db_mysql;
	$sql="DELETE FROM `ventas` WHERE `fecha_mod` >= '$fecha' AND `id_base` = '$id_base';";
	// echo "<p>$sql</p>";
	if(!mysqli_query($db_mysql,$sql)){
		return false;
	}
	return true;
}

function importar_ventas_ds(){
	global $db_ds;
	global $limite_a_importar;
//	$descuento_proveedor = importar_descuentos_proveedor();
	$data = array();
	$fecha_inicio = ultima_importacion_ventas_ds();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$numero_registros_ventas = numero_registros_ventas( $fecha_inicio, 2 );
	if( $numero_registros_ventas != $limite_a_importar ){
		if(!borrar_ventas_fecha($fecha_inicio, 2)){
			return false;
		}
	}
	$sql = "select first $limite_a_importar
	cabeza.TIPOCOMPROBANTE,
	cabeza.NUMEROCOMPROBANTE,
	cabeza.ANULADA,
	cabeza.CODIGOCLIENTE,
	cabeza.RAZONSOCIAL,
	cabeza.CODIGOUSUARIO,
	cabeza.FECHACOMPROBANTE,
	cabeza.FECHAMODIFICACION,
	cabeza.COMENTARIOS,
	cuerpo.CODIGOARTICULO,
	cuerpo.DESCRIPCION,
	cuerpo.CODIGOPARTICULAR,
	cuerpo.CODIGODEPOSITO,
	cuerpo.LINEA,
	cuerpo.CANTIDAD,
	cuerpo.PRECIOUNITARIO,
	cuerpo.COSTOVENTA,
	cabeza.DESCUENTOPORCENTAJE,
	cuerpo.DESCUENTO,
	cuerpo.PORCENTAJEIVA,
	cabeza.IVA1
	from CABEZACOMPROBANTES as cabeza
	join CUERPOCOMPROBANTES as cuerpo ON (cabeza.NUMEROCOMPROBANTE = cuerpo.NUMEROCOMPROBANTE AND cabeza.TIPOCOMPROBANTE = cuerpo.TIPOCOMPROBANTE)
	WHERE (cabeza.FECHAMODIFICACION >= '$fecha_inicio 00:00:00' OR cuerpo.FECHAMODIFICACION >= '$fecha_inicio 00:00:00')
	AND (cabeza.TIPOCOMPROBANTE = 'FA' OR cabeza.TIPOCOMPROBANTE = 'FB' OR cabeza.TIPOCOMPROBANTE = 'NCA' OR cabeza.TIPOCOMPROBANTE = 'NCB' OR cabeza.TIPOCOMPROBANTE = 'NDA' OR cabeza.TIPOCOMPROBANTE = 'NDB' OR cabeza.TIPOCOMPROBANTE = 'RE')
	ORDER BY cabeza.FECHAMODIFICACION asc;";
	$i=0;
	$res = ibase_query($db_ds, $sql);
	while($fb = ibase_fetch_object($res)){
		$data[$i]['tipo_comprobante'] = strval($fb->TIPOCOMPROBANTE);
		$data[$i]['numero_comprobante'] = strval($fb->NUMEROCOMPROBANTE);
		$data[$i]['linea'] = intval($fb->LINEA);
		$data[$i]['anulada'] = intval($fb->ANULADA);
		$data[$i]['id_cliente'] = strval($fb->CODIGOCLIENTE);
		$data[$i]['razon_social'] = strval(str_replace("'","\'",mb_convert_encoding($fb->RAZONSOCIAL, "UTF-8")));
		$data[$i]['codigo_vendedor'] = strval(str_replace("'","\'",mb_convert_encoding($fb->CODIGOUSUARIO, "UTF-8")));
		$data[$i]['fecha'] = strval($fb->FECHACOMPROBANTE);
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$data[$i]['id_articulo'] = strval($fb->CODIGOARTICULO);
		$data[$i]['articulo'] = strval(str_replace("'","\'",mb_convert_encoding($fb->DESCRIPCION, "UTF-8")));
		$data[$i]['codigoparticular'] = strval(str_replace("'","\'",mb_convert_encoding($fb->CODIGOPARTICULAR, "UTF-8")));
		$data[$i]['id_sucursal'] = strval(intval($fb->CODIGODEPOSITO));
		$data[$i]['cantidad'] = $fb->CANTIDAD;
		$data[$i]['valor_unidad'] = $fb->PRECIOUNITARIO;
		$data[$i]['costo_venta'] = $fb->COSTOVENTA * floatval(0.97);// parche descuento pago adelantado a proveedores
		if($fb->CANTIDAD!=0){
			$data[$i]['costo_unidad'] = $fb->COSTOVENTA / $fb->CANTIDAD;
		}else{
			$data[$i]['costo_unidad'] = 0;
		}
		$data[$i]['porcentaje_descuento_cliente'] = $fb->DESCUENTOPORCENTAJE;// es un numero negativo
		$data[$i]['porcentaje_descuento_marca'] = $fb->DESCUENTO;// es un numero positivo
		$data[$i]['porcentaje_iva'] = $fb->PORCENTAJEIVA;// es un numero positivo
		$data[$i]['monto_iva'] = $fb->IVA1;
		$data[$i]['descuento_op_especial'] = descuento_op_especial(str_replace("'","\'",mb_convert_encoding($fb->COMENTARIOS, "UTF-8")));
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_ventas_principal(){
	global $db_principal;
	global $limite_a_importar;
//	$descuento_proveedor = importar_descuentos_proveedor();
	$articulos = get_articulos();
	$data = array();
	$fecha_inicio = ultima_importacion_ventas_principal();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$numero_registros_ventas = numero_registros_ventas( $fecha_inicio, 1 );
	if( $numero_registros_ventas != $limite_a_importar ){
		if(!borrar_ventas_fecha($fecha_inicio, 1)){
			return false;
		}
	}
	$sql = "select first $limite_a_importar
	cabeza.TIPOCOMPROBANTE,
	cabeza.NUMEROCOMPROBANTE,
	cabeza.ANULADA,
	cabeza.CODIGOCLIENTE,
	cabeza.RAZONSOCIAL,
	cabeza.CODIGOUSUARIO,
	cabeza.FECHACOMPROBANTE,
	cabeza.FECHAMODIFICACION,
	cabeza.COMENTARIOS,
	cuerpo.CODIGOARTICULO,
	cuerpo.DESCRIPCION,
	cuerpo.CODIGOPARTICULAR,
	cuerpo.CODIGODEPOSITO,
	cuerpo.LINEA,
	cuerpo.CANTIDAD,
	cuerpo.PRECIOUNITARIO,
	cuerpo.COSTOVENTA,
	cabeza.DESCUENTOPORCENTAJE,
	cuerpo.DESCUENTO,
	cuerpo.PORCENTAJEIVA,
	cabeza.IVA1,
	clientes.BARRIO
	from CABEZACOMPROBANTES as cabeza
	join CUERPOCOMPROBANTES as cuerpo ON (cabeza.NUMEROCOMPROBANTE = cuerpo.NUMEROCOMPROBANTE AND cabeza.TIPOCOMPROBANTE = cuerpo.TIPOCOMPROBANTE)
	join CLIENTES ON cabeza.CODIGOCLIENTE = clientes.CODIGOCLIENTE
	WHERE (cabeza.FECHAMODIFICACION >= '$fecha_inicio 00:00:00' OR cuerpo.FECHAMODIFICACION >= '$fecha_inicio 00:00:00')
	AND (cabeza.TIPOCOMPROBANTE = 'FA' OR cabeza.TIPOCOMPROBANTE = 'FB' OR cabeza.TIPOCOMPROBANTE = 'NCA' OR cabeza.TIPOCOMPROBANTE = 'NCB' OR cabeza.TIPOCOMPROBANTE = 'NDA' OR cabeza.TIPOCOMPROBANTE = 'NDB' OR cabeza.TIPOCOMPROBANTE = 'RE')
	ORDER BY cabeza.FECHAMODIFICACION asc;";
	$i=0;
	$res = ibase_query($db_principal, $sql);
	while($fb = ibase_fetch_object($res)){
		$data[$i]['tipo_comprobante'] = strval($fb->TIPOCOMPROBANTE);
		$data[$i]['numero_comprobante'] = strval($fb->NUMEROCOMPROBANTE);
		$data[$i]['linea'] = intval($fb->LINEA);
		$data[$i]['anulada'] = intval($fb->ANULADA);
		$data[$i]['id_cliente'] = strval($fb->CODIGOCLIENTE);
		$data[$i]['razon_social'] = strval(str_replace("'","\'",mb_convert_encoding($fb->RAZONSOCIAL, "UTF-8")));
		$data[$i]['codigo_vendedor'] = strval(str_replace("'","\'",mb_convert_encoding($fb->CODIGOUSUARIO, "UTF-8")));
		$data[$i]['fecha'] = strval($fb->FECHACOMPROBANTE);
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$data[$i]['id_articulo'] = strval($fb->CODIGOARTICULO);
		$data[$i]['articulo'] = strval(mb_convert_encoding($fb->DESCRIPCION, "UTF-8"));
		$data[$i]['codigoparticular'] = strval(str_replace("'","\'",mb_convert_encoding($fb->CODIGOPARTICULAR, "UTF-8")));
		$data[$i]['id_sucursal'] = strval(intval($fb->CODIGODEPOSITO));
		$data[$i]['cantidad'] = $fb->CANTIDAD;
		$data[$i]['valor_unidad'] = $fb->PRECIOUNITARIO;
		$data[$i]['costo_venta'] = $fb->COSTOVENTA;
		if($fb->CANTIDAD!=0){
			$data[$i]['costo_unidad'] = $fb->COSTOVENTA / $fb->CANTIDAD;
		}else{
			$data[$i]['costo_unidad'] = 0;
		}
		$data[$i]['porcentaje_descuento_cliente'] = $fb->DESCUENTOPORCENTAJE;// es un numero negativo
		$data[$i]['porcentaje_descuento_marca'] = $fb->DESCUENTO;// es un numero positivo
		$data[$i]['porcentaje_iva'] = $fb->PORCENTAJEIVA;// es un numero positivo
		$data[$i]['monto_iva'] = $fb->IVA1;
		$data[$i]['descuento_op_especial'] = descuento_op_especial(str_replace("'","\'",mb_convert_encoding($fb->COMENTARIOS, "UTF-8")));

		/***** parche precio GM ******/

		$numeropuntoventa = intval( $data[$i]['numero_comprobante'] );
		if( 
			(($numeropuntoventa >= 1700000000 && $numeropuntoventa < 1900000000) || ($numeropuntoventa >= 170000000 && $numeropuntoventa < 190000000)) &&
			substr( strval($numeropuntoventa), 0, 6) != '189999'
			){
/*			$data[$i]['id_tipo_venta'] = 2;
			if($data[$i]['cantidad']!=0){
				$id_articulo = strval($data[$i]['id_articulo']);
				$precio_lista = $articulos[$id_articulo]['precio_lista'];
				$data[$i]['valor_unidad'] = $precio_lista / $data[$i]['cantidad'];
			}else{
				$data[$i]['valor_unidad'] = 0;
			}*/
			$data[$i]['id_tipo_venta'] = 2;
			$valor_total = $data[$i]['cantidad'] * $data[$i]['valor_unidad'];
			if( ($valor_total >= -100 && $valor_total <= 100) || $data[$i]['tipo_comprobante'] != 'RE' || $data[$i]['codigoparticular'] != 'OPESPECIAL' ){//calculo para gm, se ignora si el remito es superior a 10000 pesos o codigoparticular = OPESPECIAL
				$constante_precio_gm = 1.5385;
				$descuento_cliente_gm = descuento_barrio($fb->BARRIO);// es un numero positivo
				$descuento_cliente_gm = (100-$descuento_cliente_gm)/100;
				if($data[$i]['cantidad']!=0){
					$data[$i]['valor_unidad'] = ($fb->COSTOVENTA * $constante_precio_gm * $descuento_cliente_gm) / $data[$i]['cantidad'];
				}else{
					$data[$i]['valor_unidad'] = 0;
				}
			}
		}else{
			$data[$i]['id_tipo_venta'] = 1;
		}
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}
function importar_zonas(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_zonas();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "select first $limite_a_importar CODIGOZONA, DESCRIPCION, FECHAMODIFICACION from ZONAS 
	WHERE FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	ORDER BY DESCRIPCION asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['id_zona'] = strval($fb->CODIGOZONA);
		$data[$i]['nombre'] = strval(str_replace("'","\'",mb_convert_encoding($fb->DESCRIPCION, "UTF-8")));
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_actividades(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_actividades();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "select first $limite_a_importar CODIGOACTIVIDAD, DESCRIPCION, FECHAMODIFICACION from TIPOSACTIVIDAD 
	WHERE FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	ORDER BY DESCRIPCION asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['id_actividad'] = strval($fb->CODIGOACTIVIDAD);
		$data[$i]['nombre'] = strval(str_replace("'","\'",mb_convert_encoding($fb->DESCRIPCION, "UTF-8")));
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_descuentos_proveedor(){
	global $db_principal;
	global $limite_a_importar;
	$data = $multiplicar_pago = array();
	$query = "select first $limite_a_importar CODIGOBONIFICACIONPROV, CODIGOPROVEEDOR, FECHAVIGENCIA from BONIFICACIONESPROV
	WHERE FECHAVIGENCIA > '2010-01-01 00:00:00' order by FECHAVIGENCIA asc;";
	$res = ibase_query($db_principal, $query);
	while($dat = ibase_fetch_object($res)){
		$codigo_proveedor = strval($dat->CODIGOPROVEEDOR);
		$codigos_prov[$codigo_proveedor] = strval($dat->CODIGOBONIFICACIONPROV);
	}
	ibase_free_result( $res );
	
	$query = "select first $limite_a_importar CODIGOBONIFICACIONPROV, TIPOBONIFICACION, DIASPAGOANTICIPADO, PORCENTAJE, ORDEN
	from DETALLEBONIFICACIONESPROV WHERE TIPOBONIFICACION = '1';";// solo por pago adelantado
	$res = ibase_query($db_principal, $query);
	while($dat = ibase_fetch_object($res)){
		$codigo = strval($dat->CODIGOBONIFICACIONPROV);
		$detalles[$codigo][] = floatval($dat->PORCENTAJE);
	}
	ibase_free_result( $res );

	foreach($codigos_prov as $k=>$v){
		$multiplicar_pago[$k] = 1;
		if( isset( $detalles[$v] ) ){
			foreach($detalles[$v] as $d){
				$multiplicar_pago[$k] = $multiplicar_pago[$k] * (1-($d/100));
			}
		}
	}
#	echo "<pre>";
#	print_r($multiplicar_pago);
#	echo "</pre><br /><hr />";
	$rows = count($multiplicar_pago);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $multiplicar_pago;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_asientos($caja=''){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_asientos($caja);
	echo "<p>Fecha inicio $fecha_inicio</p>";
	$sql = "select first $limite_a_importar
	CABEZAASIENTOS.CODIGOASIENTO,
	CABEZAASIENTOS.NUMEROASIENTO,
	CABEZAASIENTOS.OBSERVACIONES,
	CABEZAASIENTOS.FECHA,
	CABEZAASIENTOS.CODIGOEJERCICIO,
	CABEZAASIENTOS.FECHAMODIFICACION as MODCABEZA,
	CUERPOASIENTOS.LINEA,
	CUERPOASIENTOS.CUENTA,
	CUERPOASIENTOS.MONTO,
	CUERPOASIENTOS.ESDEBE,
	CUERPOASIENTOS.FECHAMODIFICACION as MODCUERPO,
	iif(CABEZAASIENTOS.FECHAMODIFICACION > CUERPOASIENTOS.FECHAMODIFICACION, CABEZAASIENTOS.FECHAMODIFICACION, CUERPOASIENTOS.FECHAMODIFICACION ) as FECHA_MOD
	from CUERPOASIENTOS
	left join CABEZAASIENTOS ON CABEZAASIENTOS.CODIGOASIENTO = CUERPOASIENTOS.CODIGOASIENTO
	WHERE
	(CABEZAASIENTOS.FECHAMODIFICACION >= '$fecha_inicio' OR
	CUERPOASIENTOS.FECHAMODIFICACION >= '$fecha_inicio')";
    if( $caja == 'caja' ){
    	$sql .= " AND (CUERPOASIENTOS.CUENTA LIKE '1110100%' OR CUERPOASIENTOS.CUENTA = '11103001' OR CUERPOASIENTOS.CUENTA LIKE '11102%') ";
    }
	$sql .= " ORDER BY FECHA_MOD asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		// parche para eliminar dos asientos manuales
		if( strval($fb->CODIGOASIENTO) != '335908' || 1==1){
			//echo "<p>" . mb_detect_encoding( $fb->OBSERVACIONES ) . "</p>";
			$observaciones = str_replace("'","\'",mb_convert_encoding( $fb->OBSERVACIONES, "UTF-8"));
			$tipo = '';
			$numero = '';
			if( preg_match('/^[a-z]+/i', $observaciones, $matches) ){
				$tipo = $matches[0];
			}
			if( preg_match('/[0-9\-]+/i', $observaciones, $matches) ){
				$numero = $matches[0];
			}

			$data[$i]['codigoasiento'] = strval($fb->CODIGOASIENTO);
			$data[$i]['numeroasiento'] = strval($fb->NUMEROASIENTO);
			$data[$i]['observaciones'] = $observaciones;
			$data[$i]['fecha'] = strval($fb->FECHA);
			$data[$i]['codigoejercicio'] = strval($fb->CODIGOEJERCICIO);
			$data[$i]['linea'] = strval($fb->LINEA);
			$data[$i]['cuenta'] = strval($fb->CUENTA);
			$data[$i]['monto_total'] = floatval($fb->MONTO);
			$data[$i]['esdebe'] = strval($fb->ESDEBE);
			$data[$i]['mod_cabeza'] = strval($fb->MODCABEZA);
			$data[$i]['mod_cuerpo'] = strval($fb->MODCUERPO);
			$data[$i]['fecha_mod'] = strval($fb->FECHA_MOD);
			$data[$i]['tipocomprobante'] = $tipo;
			$data[$i]['numerocomprobante'] = $numero;
			$data[$i]['id_base'] = 1;
			$data[$i]['monto'] = $data[$i]['monto_total'];
			$i++;
		}
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$sql</p>";
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_asientos_ds($caja=''){
	global $db_ds;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_asientos_ds($caja);
	$sql = "select first $limite_a_importar
	CABEZAASIENTOS.CODIGOASIENTO,
	CABEZAASIENTOS.NUMEROASIENTO,
	CABEZAASIENTOS.OBSERVACIONES,
	CABEZAASIENTOS.FECHA,
	CABEZAASIENTOS.CODIGOEJERCICIO,
	CABEZAASIENTOS.FECHAMODIFICACION as MODCABEZA,
	CUERPOASIENTOS.LINEA,
	CUERPOASIENTOS.CUENTA,
	CUERPOASIENTOS.MONTO,
	CUERPOASIENTOS.ESDEBE,
	CUERPOASIENTOS.FECHAMODIFICACION as MODCUERPO,
	iif(CABEZAASIENTOS.FECHAMODIFICACION > CUERPOASIENTOS.FECHAMODIFICACION, CABEZAASIENTOS.FECHAMODIFICACION, CUERPOASIENTOS.FECHAMODIFICACION ) as FECHA_MOD
	from CUERPOASIENTOS
	left join CABEZAASIENTOS ON CABEZAASIENTOS.CODIGOASIENTO = CUERPOASIENTOS.CODIGOASIENTO
	WHERE
	(CABEZAASIENTOS.FECHAMODIFICACION >= '$fecha_inicio' OR
	CUERPOASIENTOS.FECHAMODIFICACION >= '$fecha_inicio')";
    if( $caja == 'caja' ){
    	$sql .= " AND (CUERPOASIENTOS.CUENTA LIKE '1110100%' OR CUERPOASIENTOS.CUENTA = '11103001' OR CUERPOASIENTOS.CUENTA LIKE '11102%') ";
    }
	$sql .= " ORDER BY FECHA_MOD asc;";
	$res = ibase_query($db_ds, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		// parche para eliminar dos asientos manuales
		if( strval($fb->CODIGOASIENTO) != '94866' || 1==1){
			$observaciones = str_replace("'","\'",mb_convert_encoding( $fb->OBSERVACIONES, "UTF-8"));

			$tipo = '';
			$numero = '';
			if( preg_match('/^[a-z]+/i', $observaciones, $matches) ){
				$tipo = $matches[0];
			}
			if( preg_match('/[0-9\-]+/i', $observaciones, $matches) ){
				$numero = $matches[0];
			}

			$data[$i]['codigoasiento'] = strval($fb->CODIGOASIENTO);
			$data[$i]['numeroasiento'] = strval($fb->NUMEROASIENTO);
			$data[$i]['observaciones'] = $observaciones;
			$data[$i]['fecha'] = strval($fb->FECHA);
			$data[$i]['codigoejercicio'] = strval($fb->CODIGOEJERCICIO);
			$data[$i]['linea'] = strval($fb->LINEA);
			$data[$i]['cuenta'] = strval($fb->CUENTA);
			$data[$i]['monto_total'] = floatval($fb->MONTO);
			$data[$i]['esdebe'] = strval($fb->ESDEBE);
			$data[$i]['mod_cabeza'] = strval($fb->MODCABEZA);
			$data[$i]['mod_cuerpo'] = strval($fb->MODCUERPO);
			$data[$i]['fecha_mod'] = strval($fb->FECHA_MOD);
			$data[$i]['tipocomprobante'] = $tipo;
			$data[$i]['numerocomprobante'] = $numero;
			$data[$i]['id_base'] = 2;
			$data[$i]['monto'] = $data[$i]['monto_total'];
			$i++;
		}
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function asientos_sin_comprobantes(){
	global $db_principal;
	global $limite_a_importar;
	$inicio = ultimo_asiento_sc();
	$asientos = array();
	$sql="select first $limite_a_importar
	CABEZAASIENTOS.CODIGOASIENTO
	from CABEZAASIENTOS
	left join COMPROBANTESASIENTOS ON CABEZAASIENTOS.CODIGOASIENTO = COMPROBANTESASIENTOS.CODIGOASIENTO
	where CABEZAASIENTOS.FECHA >= '$inicio' AND COMPROBANTESASIENTOS.TIPOCOMPROBANTE = ''
	ORDER BY CABEZAASIENTOS.FECHA ASC;";
	$res = ibase_query($db_principal, $sql);
	while($data = ibase_fetch_object($res)){
		$asientos[] = $data->CODIGOASIENTO;
	}
	ibase_free_result( $res );
	return $asientos;
}

function asignaciones_centro_costo(){
	global $db_principal;
	global $limite_a_importar;
	$inicio = ultima_asignacion_centro_costo();
	$acc = array();
	$sql="select first $limite_a_importar * FROM APROPIACIONCENTROSCOSTO WHERE CODIGOASIENTO >= '$inicio'
	ORDER BY CODIGOASIENTO ASC;";
	$i=0;
	$res = ibase_query($db_principal, $sql);
	echo "<p><br /><br />$sql</p>";
	while($data = ibase_fetch_object($res)){
		$acc[$i]['codigoasiento'] = intval($data->CODIGOASIENTO);
		$acc[$i]['linea'] = strval($data->LINEA);
		$acc[$i]['codigocentroscosto'] = strval($data->CODIGOCENTROSCOSTO);
		$acc[$i]['porcentaje'] = floatval($data->PORCENTAJE);
		$acc[$i]['monto'] = floatval($data->MONTO);
		$i++;
	}
	ibase_free_result( $res );
	return $acc;
}

function asignaciones_centro_costo_ds(){
	global $db_ds;
	global $limite_a_importar;
	$inicio = ultima_asignacion_centro_costo_ds();
	$acc = array();
	$sql="select first $limite_a_importar * FROM APROPIACIONCENTROSCOSTO WHERE CODIGOASIENTO >= '$inicio'
	ORDER BY CODIGOASIENTO ASC;";
	$i=0;
	$res = ibase_query($db_ds, $sql);
	echo "<p><br /><br />$sql</p>";
	while($data = ibase_fetch_object($res)){
		$acc[$i]['codigoasiento'] = intval($data->CODIGOASIENTO);
		$acc[$i]['linea'] = strval($data->LINEA);
		$acc[$i]['codigocentroscosto'] = strval($data->CODIGOCENTROSCOSTO);
		$acc[$i]['porcentaje'] = floatval($data->PORCENTAJE);
		$acc[$i]['monto'] = floatval($data->MONTO);
		$i++;
	}
	ibase_free_result( $res );
	return $acc;
}

function asientos_sin_comprobantes_ds(){
	global $db_ds;
	global $limite_a_importar;
	$inicio = ultimo_asiento_sc();
	$asientos = array();
	$sql="select first $limite_a_importar
	CABEZAASIENTOS.CODIGOASIENTO
	from CABEZAASIENTOS
	left join COMPROBANTESASIENTOS ON CABEZAASIENTOS.CODIGOASIENTO = COMPROBANTESASIENTOS.CODIGOASIENTO
	where CABEZAASIENTOS.FECHA >= '$inicio' AND COMPROBANTESASIENTOS.TIPOCOMPROBANTE = ''
	ORDER BY CABEZAASIENTOS.FECHA ASC;";
	$res = ibase_query($db_ds, $sql);
	while($data = ibase_fetch_object($res)){
		$asientos[] = $data->CODIGOASIENTO;
	}
	ibase_free_result( $res );
	return $asientos;
}

function importar_cheques(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_cheques();
	$sql = "select first $limite_a_importar
	CODIGOCHEQUE,
	NUMEROCHEQUE,
	NOMBRE,
	MONTO,
	ENTREGADO,
	CODIGOCAJA,
	FECHACHEQUE,
	FECHAMODIFICACION
	from CHEQUES WHERE FECHAMODIFICACION >= '$fecha_inicio' ORDER BY FECHAMODIFICACION asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['codigocheque'] = strval($fb->CODIGOCHEQUE);
		$data[$i]['numerocheque'] = strval($fb->NUMEROCHEQUE);
		$data[$i]['nombre'] = strval($fb->NOMBRE);
		$data[$i]['monto'] = floatval($fb->MONTO);
		$data[$i]['entregado'] = intval($fb->ENTREGADO);
		$data[$i]['caja'] = strval($fb->CODIGOCAJA);
		$data[$i]['fecha'] = strval($fb->FECHACHEQUE);
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$data[$i]['id_base'] = 1;
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_cheques_ds(){
	global $db_ds;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_cheques_ds();
	$sql = "select first $limite_a_importar
	CODIGOCHEQUE,
	NUMEROCHEQUE,
	NOMBRE,
	MONTO,
	ENTREGADO,
	CODIGOCAJA,
	FECHACHEQUE,
	FECHAMODIFICACION
	from CHEQUES WHERE FECHAMODIFICACION >= '$fecha_inicio' ORDER BY FECHAMODIFICACION asc;";
	$res = ibase_query($db_ds, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['codigocheque'] = strval($fb->CODIGOCHEQUE);
		$data[$i]['numerocheque'] = strval($fb->NUMEROCHEQUE);
		$data[$i]['nombre'] = strval($fb->NOMBRE);
		$data[$i]['monto'] = floatval($fb->MONTO);
		$data[$i]['entregado'] = intval($fb->ENTREGADO);
		$data[$i]['caja'] = strval($fb->CODIGOCAJA);
		$data[$i]['fecha'] = strval($fb->FECHACHEQUE);
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$data[$i]['id_base'] = 2;
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_cuerpo_asientos(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_cuerpo_asientos();
	$sql = "select first $limite_a_importar
	CODIGOASIENTO,
	LINEA,
	CUENTA,
	MONTO,
	ESDEBE,
	FECHAMODIFICACION
	from CUERPOASIENTOS	WHERE FECHAMODIFICACION >= '$fecha_inicio' ORDER BY FECHAMODIFICACION asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['codigoasiento'] = strval($fb->CODIGOASIENTO);
		$data[$i]['linea'] = strval($fb->LINEA);
		$data[$i]['cuenta'] = strval($fb->CUENTA);
		$data[$i]['monto'] = strval($fb->MONTO);
		$data[$i]['esdebe'] = strval($fb->ESDEBE);
		$data[$i]['mod_cuerpo'] = strval($fb->FECHAMODIFICACION);
		$data[$i]['id_base'] = 1;
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_cabeza_asientos($fecha_mod){
	global $db_principal;
	$data = array();
	$sql = "select first 9999 * from CABEZAASIENTOS WHERE FECHAMODIFICACION LIKE '$fecha_mod%';";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['codigoasiento'] = strval($fb->CODIGOASIENTO);
		$data[$i]['codigoejercicio'] = strval($fb->CODIGOEJERCICIO);
		$data[$i]['numeroasiento'] = strval($fb->NUMEROASIENTO);
		$data[$i]['observaciones'] = strval($fb->OBSERVACIONES);
		$data[$i]['fecha'] = strval($fb->FECHA);
		$data[$i]['codigoagrupacion'] = strval($fb->CODIGOAGRUPACION);
		$data[$i]['mod_cabeza'] = strval($fb->FECHAMODIFICACION);
		$data[$i]['id_base'] = 1;
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_comprobantes_principal(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_comprobantes_principal();
	$sql = "SELECT FIRST $limite_a_importar
	TIPOCOMPROBANTE,
	NUMEROCOMPROBANTE,
	NROPUNTODEVENTA,
	TOTAL,
	PAGADO,
	FECHACOMPROBANTE,
	FECHAMODIFICACION
	FROM CABEZACOMPROBANTES
	WHERE FECHAMODIFICACION >= '$fecha_inicio'
	ORDER BY FECHAMODIFICACION asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['tipocomprobante'] = strval($fb->TIPOCOMPROBANTE);
		$data[$i]['numerocomprobante'] = strval($fb->NUMEROCOMPROBANTE);
		$data[$i]['puntoventa'] = strval($fb->NROPUNTODEVENTA);
		$data[$i]['total'] = floatval($fb->TOTAL);
		$data[$i]['pagado'] = floatval($fb->PAGADO);
		$data[$i]['fecha'] = strval($fb->FECHACOMPROBANTE);
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$data[$i]['id_base'] = 1;
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function tablas_redi(){
	global $db_redis;
	$sql="show tables;";
	$comprobantes = array();
	$result=mysqli_query($db_redis,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$comprobantes[] = $dat;
	}
	@mysqli_free_result($result);
	if(count($comprobantes)){
		return $comprobantes;
	}else{
		return false;
	}
}

function count_rows($tabla){
	global $db_redis;
	$sql="select count(*) from $tabla;";
	$comprobantes = array();
	$result=mysqli_query($db_redis,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$comprobantes[] = $dat;
	}
	@mysqli_free_result($result);
	if(count($comprobantes)){
		return $comprobantes[0];
	}else{
		return false;
	}
}

function query($sql){
	global $db_redis;
	//$sql="show tables;";
	$comprobantes = array();
	$result=mysqli_query($db_redis,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$comprobantes[] = $dat;
	}
	@mysqli_free_result($result);
	if(count($comprobantes)){
		return $comprobantes;
	}else{
		return false;
	}
}

function importar_redi(){
	global $db_redis;
	global $limite_a_importar;
	$sql="SELECT `id`, `remitonro`, `clienteid`, `path`, `Fecha`, `estado` FROM remitosfricrot LIMIT $limite_a_importar;";
	$comprobantes = array();
	$result=mysqli_query($db_redis,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$comprobantes[] = $dat;
	}
	@mysqli_free_result($result);
	if(count($comprobantes)){
		return $comprobantes;
	}else{
		return false;
	}
}

function importar_remitos_e(){
	global $db_redis;
	global $limite_a_importar;
	$sql="SELECT
	IDRemito,
	PtoVtaNoOficial,
	NroRemNoOficial,
	IDCliente,
	NroEmpresa,
	CONCAT( IF( NroRemNoOficial = '0', IF(COT='', Concat(PuntoVta, '_', NroRemito), Concat(PuntoVta, '_', NumeroUnico) ), Concat(PtoVtaNoOficial, '-', NroRemNoOficial) ) , '__', IDRemito) as Nro_Rto,
	FechaRem,
	estado,
	Estado_Web
	FROM RemitosE LIMIT $limite_a_importar;";
	/*
	IF( COT = '', Concat( PtoVtaNoOficial, '-', NroRemNoOficial), Concat( PuntoVta, '_', NumeroUnico) ) as Nro_Rto,
	IF(NroRemNoOficial = '0', IF(COT='',Concat(PuntoVta,'', r.NroRemito),Concat(PuntoVta,'', r.NumeroUnico)) , Concat(PtoVtaNoOficial,'-', NroRemNoOficial) ) ,']','__',r.IDRemito)
	*/
	$comprobantes = array();
	$result=mysqli_query($db_redis,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$comprobantes[] = $dat;
	}
	@mysqli_free_result($result);
	if(count($comprobantes)){
		return $comprobantes;
	}else{
		return false;
	}
}

function last_remito_e_det(){
	global $db_mysql;
	$sql="SELECT `IdRemDet` FROM `RemitosEDet` ORDER BY `IdRemDet` DESC LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		return $dat['IdRemDet'];
	}
	return false;
}

function importar_remitos_e_det(){
	global $db_redis;
	global $limite_a_importar;
	$sql="SELECT IdRemDet, IdRem, Codigo, Cantidad FROM RemitosEDet LIMIT $limite_a_importar;";
	$comprobantes = array();
	$result=mysqli_query($db_redis,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$comprobantes[] = $dat;
	}
	@mysqli_free_result($result);
	if(count($comprobantes)){
		return $comprobantes;
	}else{
		return false;
	}
}

function importar_remitos_e_det_from( $IdRemDet ){
	global $db_redis;
	global $limite_a_importar;
	$sql="SELECT IdRemDet, IdRem, Codigo, Cantidad FROM RemitosEDet WHERE IdRemDet > '$IdRemDet' LIMIT $limite_a_importar;";
	$comprobantes = array();
	$result=mysqli_query($db_redis,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$comprobantes[] = $dat;
	}
	@mysqli_free_result($result);
	if(count($comprobantes)){
		return $comprobantes;
	}else{
		return false;
	}
}

function numero_cabezas_comprobantes( $fecha, $id_base ){
	global $db_mysql;
	$sql="SELECT count(`id_cabeza_comprobante`) as `numero_ventas` FROM `cabeza_comprobantes` WHERE `id_base` = '$id_base' AND `fechamodificacion` >= '$fecha' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		return $dat['numero_ventas'];
	}
	return false;
}

function borrar_cabezas_comprobantes_fecha($fecha, $id_base ){
	global $db_mysql;
	$sql="DELETE FROM `cabeza_comprobantes` WHERE `fechamodificacion` >= '$fecha' AND `id_base` = '$id_base';";
	// echo "<p>$sql</p>";
	if(!mysqli_query($db_mysql,$sql)){
		return false;
	}
	return true;
}

function importar_cabezas_comprobantes($db){
	global $db_principal;
	global $db_ds;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_cabeza_comprobantes($db);
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$numero_cabezas_comprobantes = numero_cabezas_comprobantes( $fecha_inicio, $db );
	if( $numero_cabezas_comprobantes != $limite_a_importar ){
		if(!borrar_cabezas_comprobantes_fecha($fecha_inicio, $db)){
			return false;
		}
	}
	$sql = "SELECT FIRST $limite_a_importar
	CABEZACOMPROBANTES.TIPOCOMPROBANTE,
	CABEZACOMPROBANTES.NUMEROCOMPROBANTE,
	CABEZACOMPROBANTES.CODIGOCLIENTE,
	CABEZACOMPROBANTES.FECHACOMPROBANTE,
	CABEZACOMPROBANTES.RAZONSOCIAL,
	CABEZACOMPROBANTES.DIRECCION,
	CABEZACOMPROBANTES.PORCIVA1,
	CABEZACOMPROBANTES.PORCIVA2,
	CABEZACOMPROBANTES.IVA1,
	CABEZACOMPROBANTES.IVA2,
	CABEZACOMPROBANTES.TOTAL,
	CABEZACOMPROBANTES.PAGADO,
	CABEZACOMPROBANTES.CUENTACORRIENTE,
	CABEZACOMPROBANTES.HORA,
	CABEZACOMPROBANTES.CODIGOUSUARIO,
	CABEZACOMPROBANTES.TIPOIVA,
	CABEZACOMPROBANTES.REMITOFACTURADO,
	CABEZACOMPROBANTES.COMENTARIOS,
	CABEZACOMPROBANTES.TELEFONO,
	CABEZACOMPROBANTES.FECHAVENCIMIENTO,
	CABEZACOMPROBANTES.IMPRIME,
	CABEZACOMPROBANTES.ANULADA,
	CABEZACOMPROBANTES.CUIT,
	CABEZACOMPROBANTES.COMPRA,
	CABEZACOMPROBANTES.CODIGOTRANSPORTE,
	CABEZACOMPROBANTES.MONTOTRANSPORTE,
	CABEZACOMPROBANTES.CODIGOMULTIPLAZO,
	CABEZACOMPROBANTES.EXENTO,
	CABEZACOMPROBANTES.CLASECOMPROBANTE,
	CABEZACOMPROBANTES.CODIGOUSUARIO2,
	CABEZACOMPROBANTES.COEFICIENTEIVA,
	CABEZACOMPROBANTES.FECHAMODIFICACION,
	CABEZACOMPROBANTES.DESCCOMPROBANTE,
	CABEZACOMPROBANTES.CODIGOMONEDA,
	CABEZACOMPROBANTES.COTIZACION,
	CABEZACOMPROBANTES.NUMEROTRANSACCION,
	CABEZACOMPROBANTES.CANTIDADBULTOS,
	CABEZACOMPROBANTES.NROPUNTODEVENTA,
	CABEZACOMPROBANTES.CODIGOPROYECTO,
	CABEZACOMPROBANTES.DESCUENTOPORCENTAJE,
	CABEZACOMPROBANTES.DESCUENTOMONTO,
	CABEZACOMPROBANTES.DESCUENTODESCRIPCION,
	CABEZACOMPROBANTES.CANTIDADPAGINAS,
	CABEZACOMPROBANTES.LISTAPRECIO,
	CABEZACOMPROBANTES.VALIDACTACTE,
	CABEZACOMPROBANTES.MONTOTOTALII,
	CABEZACOMPROBANTES.FECHAVENCIMIENTO2,
	CABEZACOMPROBANTES.RECARGOVENCIMIENTO2,
	CABEZACOMPROBANTES.FECHAVENCIMIENTO3,
	CABEZACOMPROBANTES.RECARGOVENCIMIENTO3,
	CUERPOCOMPROBANTES.CODIGODEPOSITO
	FROM CABEZACOMPROBANTES
	LEFT JOIN CUERPOCOMPROBANTES ON CUERPOCOMPROBANTES.TIPOCOMPROBANTE = CABEZACOMPROBANTES.TIPOCOMPROBANTE 
	AND CUERPOCOMPROBANTES.NUMEROCOMPROBANTE = CABEZACOMPROBANTES.NUMEROCOMPROBANTE
	AND CUERPOCOMPROBANTES.LINEA = 1
	WHERE CABEZACOMPROBANTES.FECHAMODIFICACION >= '$fecha_inicio'
	ORDER BY CABEZACOMPROBANTES.FECHAMODIFICACION asc;";
	if( $db == 1 ){
		$res = ibase_query($db_principal, $sql);
	}else{
		$res = ibase_query($db_ds, $sql);
	}
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['tipocomprobante'] = str_replace("'","\'",mb_convert_encoding($fb->TIPOCOMPROBANTE, "UTF-8"));
		$data[$i]['numerocomprobante'] = $fb->NUMEROCOMPROBANTE;
		$data[$i]['codigocliente'] = str_replace("'","\'",mb_convert_encoding($fb->CODIGOCLIENTE, "UTF-8"));
		$data[$i]['fechacomprobante'] = $fb->FECHACOMPROBANTE;
		$data[$i]['razonsocial'] = str_replace("'","\'",mb_convert_encoding($fb->RAZONSOCIAL, "UTF-8"));
		$data[$i]['direccion'] = str_replace("'","\'",mb_convert_encoding($fb->DIRECCION, "UTF-8"));
		$data[$i]['porciva1'] = $fb->PORCIVA1;
		$data[$i]['porciva2'] = $fb->PORCIVA2;
		$data[$i]['iva1'] = $fb->IVA1;
		$data[$i]['iva2'] = $fb->IVA2;
		$data[$i]['total'] = $fb->TOTAL;
		$data[$i]['pagado'] = $fb->PAGADO;
		$data[$i]['cuentacorriente'] = str_replace("'","\'",mb_convert_encoding($fb->CUENTACORRIENTE, "UTF-8"));
		$data[$i]['hora'] = $fb->HORA;
		$data[$i]['codigousuario'] = str_replace("'","\'",mb_convert_encoding($fb->CODIGOUSUARIO, "UTF-8"));
		$data[$i]['tipoiva'] = str_replace("'","\'",mb_convert_encoding($fb->TIPOIVA, "UTF-8"));
		$data[$i]['remitofacturado'] = str_replace("'","\'",mb_convert_encoding($fb->REMITOFACTURADO, "UTF-8"));
		$data[$i]['comentarios'] = str_replace("'","\'",mb_convert_encoding($fb->COMENTARIOS, "UTF-8"));
		$data[$i]['telefono'] = str_replace("'","\'",mb_convert_encoding($fb->TELEFONO, "UTF-8"));
		$data[$i]['fechavencimiento'] = $fb->FECHAVENCIMIENTO;
		$data[$i]['imprime'] = str_replace("'","\'",mb_convert_encoding($fb->IMPRIME, "UTF-8"));
		$data[$i]['anulada'] = str_replace("'","\'",mb_convert_encoding($fb->ANULADA, "UTF-8"));
		$data[$i]['cuit'] = str_replace("'","\'",mb_convert_encoding($fb->CUIT, "UTF-8"));
		$data[$i]['compra'] = $fb->COMPRA;
		$data[$i]['codigotransporte'] = $fb->CODIGOTRANSPORTE;
		$data[$i]['montotransporte'] = $fb->MONTOTRANSPORTE;
		$data[$i]['codigomultiplazo'] = $fb->CODIGOMULTIPLAZO;
		$data[$i]['exento'] = $fb->EXENTO;
		$data[$i]['clasecomprobante'] = $fb->CLASECOMPROBANTE;
		$data[$i]['codigousuario2'] = str_replace("'","\'",mb_convert_encoding($fb->CODIGOUSUARIO2, "UTF-8"));
		$data[$i]['coeficienteiva'] = $fb->COEFICIENTEIVA;
		$data[$i]['fechamodificacion'] = $fb->FECHAMODIFICACION;
		$data[$i]['desccomprobante'] = str_replace("'","\'",mb_convert_encoding($fb->DESCCOMPROBANTE, "UTF-8"));
		$data[$i]['codigomoneda'] = str_replace("'","\'",mb_convert_encoding($fb->CODIGOMONEDA, "UTF-8"));
		$data[$i]['cotizacion'] = $fb->COTIZACION;
		$data[$i]['numerotransaccion'] = $fb->NUMEROTRANSACCION;
		$data[$i]['cantidadbultos'] = $fb->CANTIDADBULTOS;
		$data[$i]['nropuntodeventa'] = $fb->NROPUNTODEVENTA;
		$data[$i]['codigoproyecto'] = str_replace("'","\'",mb_convert_encoding($fb->CODIGOPROYECTO, "UTF-8"));
		$data[$i]['descuentoporcentaje'] = $fb->DESCUENTOPORCENTAJE;
		$data[$i]['descuentomonto'] = $fb->DESCUENTOMONTO;
		$data[$i]['descuentodescripcion'] = str_replace("'","\'",mb_convert_encoding($fb->DESCUENTODESCRIPCION, "UTF-8"));
		$data[$i]['cantidadpaginas'] = $fb->CANTIDADPAGINAS;
		$data[$i]['listaprecio'] = $fb->LISTAPRECIO;
		$data[$i]['validactacte'] = $fb->VALIDACTACTE;
		$data[$i]['montototalii'] = $fb->MONTOTOTALII;
		$data[$i]['fechavencimiento2'] = $fb->FECHAVENCIMIENTO2;
		$data[$i]['recargovencimiento2'] = $fb->RECARGOVENCIMIENTO2;
		$data[$i]['fechavencimiento3'] = $fb->FECHAVENCIMIENTO3;
		$data[$i]['recargovencimiento3'] = $fb->RECARGOVENCIMIENTO3;
		$data[$i]['codigodeposito'] = $fb->CODIGODEPOSITO;
		$data[$i]['id_base'] = $db;
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function numero_cuerpos_comprobantes( $fecha, $id_base ){
	global $db_mysql;
	$sql="SELECT count(`id_cuerpo_comprobante`) as `numero_lineas` FROM `cuerpo_comprobantes` WHERE `id_base` = '$id_base' AND `fechamodificacion` >= '$fecha' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		return $dat['numero_lineas'];
	}
	return false;
}

function borrar_cuerpos_comprobantes_fecha($fecha, $id_base ){
	global $db_mysql;
	$sql="DELETE FROM `cuerpo_comprobantes` WHERE `fechamodificacion` >= '$fecha' AND `id_base` = '$id_base';";
	// echo "<p>$sql</p>";
	if(!mysqli_query($db_mysql,$sql)){
		return false;
	}
	return true;
}

function importar_cuerpos_comprobantes($db){
	global $db_principal;
	global $db_ds;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_cuerpo_comprobantes($db);
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$numero_cuerpos_comprobantes = numero_cuerpos_comprobantes( $fecha_inicio, $db );
	if( $numero_cuerpos_comprobantes != $limite_a_importar ){
		if(!borrar_cuerpos_comprobantes_fecha($fecha_inicio, $db)){
			return false;
		}
	}
	$sql = "SELECT FIRST $limite_a_importar
	TIPOCOMPROBANTE,
	NUMEROCOMPROBANTE,
	LINEA,
	CODIGOARTICULO,
	DESCRIPCION,
	CANTIDAD,
	DESCUENTO,
	PRECIOUNITARIO,
	PRECIOTOTAL,
	GARANTIA,
	INTERES,
	CANTIDADREMITIDA,
	LOTE,
	ESCONJUNTO,
	FECHAMODIFICACION,
	CODIGODEPOSITO,
	COSTOVENTA,
	NUMEROTRANSACCION,
	CODIGOPARTICULAR,
	PORCENTAJEIVA,
	DESCDESCUENTO,
	TIPOPRECIO,
	PORCENTAJEDESCUENTOS,
	MONTOII,
	COEFICIENTECONVERSION,
	CODIGOEMPAQUE,
	DESCRIPCIONEMPAQUE,
	OBSERVACIONES
	FROM CUERPOCOMPROBANTES
	WHERE FECHAMODIFICACION >= '$fecha_inicio'
	ORDER BY FECHAMODIFICACION asc;";
	if( $db == 1 ){
		$res = ibase_query($db_principal, $sql);
	}else{
		$res = ibase_query($db_ds, $sql);
	}
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['tipocomprobante'] = str_replace("'","\'",mb_convert_encoding($fb->TIPOCOMPROBANTE, "UTF-8"));
		$data[$i]['numerocomprobante'] = $fb->NUMEROCOMPROBANTE;
		$data[$i]['linea'] = $fb->LINEA;
		$data[$i]['codigoarticulo'] = str_replace("'","\'",mb_convert_encoding($fb->CODIGOARTICULO, "UTF-8"));
		$data[$i]['descripcion'] = str_replace("'","\'",mb_convert_encoding($fb->DESCRIPCION, "UTF-8"));
		$data[$i]['cantidad'] = $fb->CANTIDAD;
		$data[$i]['descuento'] = $fb->DESCUENTO;
		$data[$i]['preciounitario'] = $fb->PRECIOUNITARIO;
		$data[$i]['preciototal'] = $fb->PRECIOTOTAL;
		$data[$i]['garantia'] = $fb->GARANTIA;
		$data[$i]['interes'] = $fb->INTERES;
		$data[$i]['cantidadremitida'] = $fb->CANTIDADREMITIDA;
		$data[$i]['lote'] = str_replace("'","\'",mb_convert_encoding($fb->LOTE, "UTF-8"));
		$data[$i]['esconjunto'] = str_replace("'","\'",mb_convert_encoding($fb->ESCONJUNTO, "UTF-8"));
		$data[$i]['fechamodificacion'] = $fb->FECHAMODIFICACION;
		$data[$i]['codigodeposito'] = str_replace("'","\'",mb_convert_encoding($fb->CODIGODEPOSITO, "UTF-8"));
		$data[$i]['costoventa'] = $fb->COSTOVENTA;
		$data[$i]['numerotransaccion'] = $fb->NUMEROTRANSACCION;
		$data[$i]['codigoparticular'] = str_replace("'","\'",mb_convert_encoding($fb->CODIGOPARTICULAR, "UTF-8"));
		$data[$i]['porcentajeiva'] = $fb->PORCENTAJEIVA;
		$data[$i]['descdescuento'] = str_replace("'","\'",mb_convert_encoding($fb->DESCDESCUENTO, "UTF-8"));
		$data[$i]['tipoprecio'] = str_replace("'","\'",mb_convert_encoding($fb->TIPOPRECIO, "UTF-8"));
		$data[$i]['porcentajedescuentos'] = $fb->PORCENTAJEDESCUENTOS;
		$data[$i]['montoii'] = $fb->MONTOII;
		$data[$i]['coeficienteconversion'] = $fb->COEFICIENTECONVERSION;
		$data[$i]['codigoempaque'] = str_replace("'","\'",mb_convert_encoding($fb->CODIGOEMPAQUE, "UTF-8"));
		$data[$i]['descripcionempaque'] = str_replace("'","\'",mb_convert_encoding($fb->DESCRIPCIONEMPAQUE, "UTF-8"));
		$data[$i]['observaciones'] = str_replace("'","\'",mb_convert_encoding($fb->OBSERVACIONES, "UTF-8"));
		$data[$i]['id_base'] = $db;
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_cae_afip(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fechahorasolicitud = ultima_importacion_cae_afip();
	$sql = "SELECT FIRST $limite_a_importar
	NUMEROTRANSACCIONAFIP,
	TIPOCOMPROBANTE,
	NUMEROCOMPROBANTE,
	CAE,
	VENCIMIENTOCAE,
	FECHAHORASOLICITUD,
	RESPUESTAAFIP,
	IMPTOTAL,
	IMPNETO,
	IMPOPEX,
	IMPIVA,
	IMPTRIB,
	INFORMADO
	FROM CAEAFIP
	WHERE FECHAHORASOLICITUD >= '$fechahorasolicitud'
	ORDER BY FECHAHORASOLICITUD asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['numerotransaccionafip'] = $fb->NUMEROTRANSACCIONAFIP;
		$data[$i]['tipocomprobante'] = str_replace("'","\'",mb_convert_encoding($fb->TIPOCOMPROBANTE, "UTF-8"));
		$data[$i]['numerocomprobante'] = $fb->NUMEROCOMPROBANTE;
		$data[$i]['cae'] = str_replace("'","\'",mb_convert_encoding($fb->CAE, "UTF-8"));
		$data[$i]['vencimientocae'] = $fb->VENCIMIENTOCAE;
		$data[$i]['fechahorasolicitud'] = $fb->FECHAHORASOLICITUD;
		$data[$i]['respuestaafip'] = str_replace("'","\'",mb_convert_encoding($fb->RESPUESTAAFIP, "UTF-8"));
		$data[$i]['imptotal'] = $fb->IMPTOTAL;
		$data[$i]['impneto'] = $fb->IMPNETO;
		$data[$i]['impopex'] = $fb->IMPOPEX;
		$data[$i]['impiva'] = $fb->IMPIVA;
		$data[$i]['imptrib'] = $fb->IMPTRIB;
		$data[$i]['informado'] = str_replace("'","\'",mb_convert_encoding($fb->INFORMADO, "UTF-8"));
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
//	echo "<p>$sql</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}


function importar_descuentos_clientes_marcas(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$sql = "SELECT FIRST $limite_a_importar * FROM DESCUENTOCLIENTESMARCAS;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['codigo_cliente'] = $fb->CODIGOCLIENTE;
		$data[$i]['codigo_marca'] = $fb->CODIGOMARCA;
		$data[$i]['porcentaje_descuento'] = $fb->PORCENTAJEDESCUENTO;
		$data[$i]['fecha_mod'] = $fb->FECHAMODIFICACION;
		$data[$i]['numero_transaccion'] = $fb->NUMEROTRANSACCION;
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
//	echo "<p>$sql</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function actualizar_descuento_cliente_marca($descuento_cliente_marca){
	global $db_mysql;
	$codigo_cliente = $descuento_cliente_marca['codigo_cliente'];
	$codigo_marca = $descuento_cliente_marca['codigo_marca'];
	$porcentaje_descuento = $descuento_cliente_marca['porcentaje_descuento'];
	$fecha_mod = $descuento_cliente_marca['fecha_mod'];
	$numero_transaccion = $descuento_cliente_marca['numero_transaccion'];

	@mysqli_free_result($result);
	$sql="INSERT INTO `descuentos_clientes_marcas` SET
	`codigo_cliente` = '$codigo_cliente',
	`codigo_marca` = '$codigo_marca',
	`porcentaje_descuento` = '$porcentaje_descuento',
	`fecha_mod` = '$fecha_mod',
	`numero_transaccion` = '$numero_transaccion';";
//	echo "<p>$sql</p>";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function importar_pedidos_pendientes(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	if( borrar_pedidos_recibidos() ){
		$fecha_inicio = ultima_importacion_pedidos_pendientes();
		$sql = "SELECT FIRST $limite_a_importar
		CABEZAORDENESCOMPRAS.TIPOCOMPROBANTE,
		CABEZAORDENESCOMPRAS.NUMEROCOMPROBANTE,
		CABEZAORDENESCOMPRAS.CODIGODEPOSITO,
		CABEZAORDENESCOMPRAS.CODIGOPROVEEDOR,
		CABEZAORDENESCOMPRAS.FECHACOMPROBANTE,
		CABEZAORDENESCOMPRAS.FECHAREQUERIDA,
		CABEZAORDENESCOMPRAS.ANULADA,
		CUERPOORDENESCOMPRAS.LINEA,
		CUERPOORDENESCOMPRAS.CODIGOARTICULO,
		CUERPOORDENESCOMPRAS.CANTIDADPEDIDA,
		CUERPOORDENESCOMPRAS.CANTIDADRECIBIDA,
		CUERPOORDENESCOMPRAS.FECHAMODIFICACION,
		CUERPOORDENESCOMPRAS.ANULADO
		FROM CABEZAORDENESCOMPRAS
		LEFT JOIN CUERPOORDENESCOMPRAS
		ON CABEZAORDENESCOMPRAS.TIPOCOMPROBANTE = CUERPOORDENESCOMPRAS.TIPOCOMPROBANTE AND CABEZAORDENESCOMPRAS.NUMEROCOMPROBANTE = CUERPOORDENESCOMPRAS.NUMEROCOMPROBANTE
		WHERE CUERPOORDENESCOMPRAS.FECHAMODIFICACION >= '$fecha_inicio' AND
		CUERPOORDENESCOMPRAS.CANTIDADPEDIDA != CUERPOORDENESCOMPRAS.CANTIDADRECIBIDA
		ORDER BY CUERPOORDENESCOMPRAS.FECHAMODIFICACION asc;";
		$res = ibase_query($db_principal, $sql);
		$i=0;
		while($fb = ibase_fetch_object($res)){
			$data[$i]['tipo'] = strval($fb->TIPOCOMPROBANTE);
			$data[$i]['numero'] = strval($fb->NUMEROCOMPROBANTE);
			$data[$i]['deposito'] = strval($fb->CODIGODEPOSITO);
			$data[$i]['proveedor'] = strval($fb->CODIGOPROVEEDOR);
			$data[$i]['linea'] = floatval($fb->LINEA);
			$data[$i]['articulo'] = strval($fb->CODIGOARTICULO);
			$data[$i]['pedidos'] = strval($fb->CANTIDADPEDIDA);
			$data[$i]['recibidos'] = strval($fb->CANTIDADRECIBIDA);
			$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
			$data[$i]['fecha_comprobante'] = strval($fb->FECHACOMPROBANTE);
			$data[$i]['fecha_requerida'] = strval($fb->FECHAREQUERIDA);
			$data[$i]['anulado'] = strval($fb->ANULADO);
			$data[$i]['anulada'] = strval($fb->ANULADA);
			$i++;
		}
		ibase_free_result( $res );
		$rows = count($data);
		//echo "<p>$rows</p>";
		if($rows>0){
			return $data;
		}elseif($rows===0){
			return '0';
		}else{
			return false;
		}
	}
	return false;
}

function actualizar_pedido_pendiente($pedido){
	global $db_mysql;
	$tipo = $pedido['tipo'];
	$numero = $pedido['numero'];
	$deposito = $pedido['deposito'];
	$proveedor = $pedido['proveedor'];
	$linea = $pedido['linea'];
	$articulo = $pedido['articulo'];
	$pedidos = $pedido['pedidos'];
	$recibidos = $pedido['recibidos'];
	$fecha_mod = $pedido['fecha_mod'];
	$fecha_comprobante = $pedido['fecha_comprobante'];
	$fecha_requerida = $pedido['fecha_requerida'];
	$anulado = $pedido['anulado'];
	$anulada = $pedido['anulada'];
	$sql="SELECT `id_pedido` FROM `pendientes` WHERE `tipo` = '$tipo' AND `numero` = '$numero' AND `linea` = '$linea' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$id_pedido = $dat['id_pedido'];
		$sql="UPDATE `pendientes` SET 
		`deposito` = '$deposito', 
		`proveedor` = '$proveedor', 
		`articulo` = '$articulo', 
		`pedidos` = '$pedidos', 
		`recibidos` = '$recibidos', 
		`fecha_mod` = '$fecha_mod', 
		`fecha_comprobante` = '$fecha_comprobante', 
		`fecha_requerida` = '$fecha_requerida', 
		`anulado` = '$anulado', 
		`anulada` = '$anulada' 
		WHERE `id_pedido` = '$id_pedido';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
		return false;
	}else{
		@mysqli_free_result($result);
		if( $anulado == 0 && $anulada == 0 ){
			$sql="INSERT INTO `pendientes` SET 
			`tipo` = '$tipo', 
			`numero` = '$numero', 
			`deposito` = '$deposito', 
			`proveedor` = '$proveedor', 
			`linea` = '$linea', 
			`articulo` = '$articulo', 
			`pedidos` = '$pedidos', 
			`recibidos` = '$recibidos', 
			`fecha_mod` = '$fecha_mod',
			`fecha_comprobante` = '$fecha_comprobante', 
			`fecha_requerida` = '$fecha_requerida', 
			`anulado` = '$anulado', 
			`anulada` = '$anulada';";
			if(mysqli_query($db_mysql,$sql)){
				return true;
			}
			return false;
		}
		return true;
	}
//	echo "<p>$sql</p>";
	return false;
}

function coeficiente_precio($descripcion){
	$descripcion = str_replace(array('(',')'),'',$descripcion);
	$descripcion = trim($descripcion);
	$coeficiente_precio = floatval(1);
	$array_descuentos = explode('+',$descripcion);
	foreach( $array_descuentos as $descuento ){
		$null = explode('PA',$descuento);
		$descuento = str_replace(',','.',$null[0]);
		$coeficiente_precio = floatval($coeficiente_precio) * ((floatval(100)-floatval($descuento))/100);
	}
	return $coeficiente_precio;
}

function importar_bonificaciones_proveedor(){
	global $db_principal;
	$data = array();
	if( borrar_bonificaciones_proveedor() ){
		$sql = "SELECT * FROM BONIFICACIONESPROV ORDER BY FECHAMODIFICACION ASC, CODIGOBONIFICACIONPROV ASC;";
		$res = ibase_query($db_principal, $sql);
		$i=0;
		while($fb = ibase_fetch_object($res)){
			$data[$i]['codigo_bonificacion'] = strval($fb->CODIGOBONIFICACIONPROV);
			$data[$i]['codigo_proveedor'] = strval($fb->CODIGOPROVEEDOR);
			$data[$i]['descripcion'] = strval(str_replace("'","\'",mb_convert_encoding($fb->DESCRIPCION, "UTF-8")));
			$data[$i]['fecha_vigencia'] = strval($fb->FECHAVIGENCIA);
			$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
			$data[$i]['coeficiente_precio'] = coeficiente_precio($data[$i]['descripcion']);
			$i++;
		}
		ibase_free_result( $res );
		$rows = count($data);
		//echo "<p>$rows</p>";
		if($rows>0){
			return $data;
		}elseif($rows===0){
			return '0';
		}else{
			return false;
		}
	}
	return false;
}


function actualizar_bonificacion_proveedor($bonificacion){
	global $db_mysql;
	$codigo_bonificacion = $bonificacion['codigo_bonificacion'];
	$codigo_proveedor = $bonificacion['codigo_proveedor'];
	$descripcion = $bonificacion['descripcion'];
	$fecha_vigencia = $bonificacion['fecha_vigencia'];
	$fecha_mod = $bonificacion['fecha_mod'];
	$coeficiente_precio = $bonificacion['coeficiente_precio'];
	$sql="INSERT INTO `bonificaciones` SET `codigo_bonificacion` = '$codigo_bonificacion', `codigo_proveedor` = '$codigo_proveedor', `descripcion` = '$descripcion', `fecha_vigencia` = '$fecha_vigencia', `fecha_mod` = '$fecha_mod', `coeficiente_precio` = '$coeficiente_precio';";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function actualizar_plan_de_cuenta( $linea ){
	global $db_mysql;
	$cuenta = $linea['cuenta'];
	$descripcion = $linea['descripcion'];
	$cuenta_madre = $linea['cuenta_madre'];
	$ingresos = $linea['ingresos'];
	$egresos = $linea['egresos'];
	$activa = $linea['activa'];
	$imputable = $linea['imputable'];
	$patrimonial = $linea['patrimonial'];
	$utilizacioncentrocosto = $linea['utilizacioncentrocosto'];
	$sql="INSERT INTO `plan de cuentas` SET 
	`cuenta` = '$cuenta', 
	`descripcion` = '$descripcion', 
	`cuenta_madre` = '$cuenta_madre',
	`ingresos` = '$ingresos',
	`egresos` = '$egresos',
	`activa` = '$activa',
	`imputable` = '$imputable',
	`patrimonial` = '$patrimonial',
	`utilizacioncentrocosto` = '$utilizacioncentrocosto';";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

/************** importacion compras **************/

function ultima_importacion_compras(){
	global $db_mysql;
	$sql="SELECT fecha_mod FROM `compras` ORDER BY fecha_mod desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		return $dat['fecha_mod'];
	}else{
		return '2011-06-01 00:00:00';
		#return '2013-09-01';
	}
}

function importar_compras(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_compras();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "SELECT first $limite_a_importar
	CABEZA.FECHACOMPROBANTE,
	CABEZA.RAZONSOCIAL,
	CABEZA.ANULADA,
	CABEZA.CLASECOMPROBANTE,
	CUERPO.TIPOCOMPROBANTE,
	CUERPO.NUMEROCOMPROBANTE,
	CUERPO.LINEA,
	CUERPO.CODIGOPROVEEDOR,
	CUERPO.CODIGOARTICULO,
	CUERPO.DESCRIPCION,
	CUERPO.CANTIDAD,
	CUERPO.PRECIOUNITARIO,
	CUERPO.PRECIOTOTAL,
	CUERPO.FECHAMODIFICACION
	FROM CABEZACOMPRAS as CABEZA
	JOIN CUERPOCOMPRAS as CUERPO ON (CUERPO.NUMEROCOMPROBANTE = CABEZA.NUMEROCOMPROBANTE AND CUERPO.TIPOCOMPROBANTE = CABEZA.TIPOCOMPROBANTE)
	WHERE (CABEZA.TIPOCOMPROBANTE = 'FA' OR CABEZA.TIPOCOMPROBANTE = 'FB' OR CABEZA.TIPOCOMPROBANTE = 'NCA' OR CABEZA.TIPOCOMPROBANTE = 'NCB')
	AND CUERPO.FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	ORDER BY CUERPO.FECHAMODIFICACION asc;";
	$i=0;
	$res = ibase_query($db_principal, $sql);
	while($fb = ibase_fetch_object($res)){
		$data[$i]['fecha'] = strval($fb->FECHACOMPROBANTE);
		$data[$i]['razonsocial'] = strval(str_replace("'","\'",mb_convert_encoding($fb->RAZONSOCIAL, "UTF-8")));
		$data[$i]['anulada'] = strval($fb->ANULADA);
		$data[$i]['clase'] = strval($fb->CLASECOMPROBANTE);
		$data[$i]['tipo'] = strval($fb->TIPOCOMPROBANTE);
		$data[$i]['numero'] = strval($fb->NUMEROCOMPROBANTE);
		$data[$i]['linea'] = strval($fb->LINEA);
		$data[$i]['codigoproveedor'] = strval($fb->CODIGOPROVEEDOR);
		$data[$i]['codigoarticulo'] = strval($fb->CODIGOARTICULO);
		$data[$i]['descripcion'] = strval(str_replace("'","\'",mb_convert_encoding($fb->DESCRIPCION, "UTF-8")));
		$data[$i]['cantidad'] = strval($fb->CANTIDAD);
		$data[$i]['preciounitario'] = strval($fb->PRECIOUNITARIO);
		$data[$i]['total'] = strval($fb->PRECIOTOTAL);
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
//	echo "<p>rows: $rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}


function actualizar_compras($compra){
	global $db_mysql;
	$tipo = $compra['tipo'];
	$numero = $compra['numero'];
	$linea = $compra['linea'];
	$fecha = $compra['fecha'];
	$razonsocial = $compra['razonsocial'];
	$anulada = $compra['anulada'];
	$clase = $compra['clase'];
	$codigoproveedor = $compra['codigoproveedor'];
	$codigoarticulo = $compra['codigoarticulo'];
	$descripcion = $compra['descripcion'];
	$cantidad = $compra['cantidad'];
	$preciounitario = $compra['preciounitario'];
	$total = $compra['total'];
	$fecha_mod = $compra['fecha_mod'];
	$sql="SELECT `id_compra` FROM `compras` WHERE `tipo` = '$tipo' AND `numero` = '$numero' AND `linea` = '$linea' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$id_compra = $dat['id_compra'];
		$sql="UPDATE `compras` SET `tipo` = '$tipo', `numero` = '$numero', `linea` = '$linea', `fecha` = '$fecha', `razonsocial` = '$razonsocial', `anulada` = '$anulada', `clase` = '$clase', `codigoproveedor` = '$codigoproveedor', `codigoarticulo` = '$codigoarticulo', `descripcion` = '$descripcion', `cantidad` = '$cantidad', `preciounitario` = '$preciounitario', `total` = '$total', `fecha_mod` = '$fecha_mod' WHERE `id_compra` = '$id_compra';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `compras` SET `tipo` = '$tipo', `numero` = '$numero', `linea` = '$linea', `fecha` = '$fecha', `razonsocial` = '$razonsocial', `anulada` = '$anulada', `clase` = '$clase', `codigoproveedor` = '$codigoproveedor', `codigoarticulo` = '$codigoarticulo', `descripcion` = '$descripcion', `cantidad` = '$cantidad', `preciounitario` = '$preciounitario', `total` = '$total', `fecha_mod` = '$fecha_mod';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
//	echo "<p>$sql</p>";
	return false;
}

/************** costos fijos **************/

function ultima_importacion_costos_fijos_principal(){
	global $db_mysql;
	$sql="SELECT fecha_mod FROM `costos_fijos` WHERE `id_base` = '1' ORDER BY fecha_mod desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return $dat['fecha_mod'];
	}else{
		@mysqli_free_result($result);
		return '2011-06-01 00:00:00';
		#return '2013-09-01';
	}
}
function ultima_importacion_costos_fijos_ds(){
	global $db_mysql;
	$sql="SELECT fecha_mod FROM `costos_fijos` WHERE `id_base` = '2' ORDER BY fecha_mod desc LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return $dat['fecha_mod'];
	}else{
		@mysqli_free_result($result);
		return '2011-06-01 00:00:00';
		#return '2013-09-01';
	}
}

function importar_costos_fijos_db_ds(){
	global $db_ds;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_costos_fijos_ds();
	$sql = "select first $limite_a_importar CABEZAASIENTOS.CODIGOASIENTO, CABEZAASIENTOS.NUMEROASIENTO, CUERPOASIENTOS.LINEA, CABEZAASIENTOS.OBSERVACIONES, CABEZACOMPRAS.COMENTARIOS, CABEZAASIENTOS.FECHA, CABEZAASIENTOS.FECHAMODIFICACION, CUERPOASIENTOS.MONTO, CUERPOASIENTOS.ESDEBE, CUERPOASIENTOS.CUENTA, APROPIACIONCENTROSCOSTO.CODIGOCENTROSCOSTO, APROPIACIONCENTROSCOSTO.PORCENTAJE from CABEZAASIENTOS
	join CUERPOASIENTOS ON CABEZAASIENTOS.CODIGOASIENTO = CUERPOASIENTOS.CODIGOASIENTO
	left join APROPIACIONCENTROSCOSTO ON (CUERPOASIENTOS.CODIGOASIENTO = APROPIACIONCENTROSCOSTO.CODIGOASIENTO AND CUERPOASIENTOS.LINEA = APROPIACIONCENTROSCOSTO.LINEA)
	left join COMPROBANTESASIENTOS ON CABEZAASIENTOS.CODIGOASIENTO = COMPROBANTESASIENTOS.CODIGOASIENTO
	left join CABEZACOMPRAS ON (COMPROBANTESASIENTOS.TIPOCOMPROBANTE = CABEZACOMPRAS.TIPOCOMPROBANTE AND COMPROBANTESASIENTOS.NUMEROCOMPROBANTE = CABEZACOMPRAS.NUMEROCOMPROBANTE)
	WHERE CABEZAASIENTOS.FECHAMODIFICACION >= '$fecha_inicio' AND (CUERPOASIENTOS.CUENTA LIKE '422%' OR CUERPOASIENTOS.CUENTA LIKE '423%' OR CUERPOASIENTOS.CUENTA LIKE '424%') ORDER BY CABEZAASIENTOS.FECHAMODIFICACION asc;";
	$res = ibase_query($db_ds, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['codigoasiento'] = strval($fb->CODIGOASIENTO);
		$data[$i]['numeroasiento'] = strval($fb->NUMEROASIENTO);
		$data[$i]['linea'] = strval($fb->LINEA);
		$data[$i]['cuenta'] = strval($fb->CUENTA);
		$data[$i]['observaciones'] = strval($fb->OBSERVACIONES);
		$data[$i]['comentarios'] = strval($fb->COMENTARIOS);
		$data[$i]['esdebe'] = strval($fb->ESDEBE);
		$data[$i]['porcentaje'] = strval($fb->PORCENTAJE);
		$data[$i]['monto'] = strval($fb->MONTO);
		$data[$i]['fecha'] = strval($fb->FECHA);
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$data[$i]['codigocentrocosto'] = strval($fb->CODIGOCENTROSCOSTO);
		$data[$i]['id_base'] = 2;
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function importar_costos_fijos_db_principal(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_costos_fijos_principal();
	$sql = "select first $limite_a_importar CABEZAASIENTOS.CODIGOASIENTO, CABEZAASIENTOS.NUMEROASIENTO, CUERPOASIENTOS.LINEA, CABEZAASIENTOS.OBSERVACIONES, CABEZACOMPRAS.COMENTARIOS, CABEZAASIENTOS.FECHA, CABEZAASIENTOS.FECHAMODIFICACION, CUERPOASIENTOS.MONTO, CUERPOASIENTOS.ESDEBE, CUERPOASIENTOS.CUENTA, APROPIACIONCENTROSCOSTO.CODIGOCENTROSCOSTO, APROPIACIONCENTROSCOSTO.PORCENTAJE from CABEZAASIENTOS
	join CUERPOASIENTOS ON CABEZAASIENTOS.CODIGOASIENTO = CUERPOASIENTOS.CODIGOASIENTO
	left join APROPIACIONCENTROSCOSTO ON (CUERPOASIENTOS.CODIGOASIENTO = APROPIACIONCENTROSCOSTO.CODIGOASIENTO AND CUERPOASIENTOS.LINEA = APROPIACIONCENTROSCOSTO.LINEA)
	left join COMPROBANTESASIENTOS ON CABEZAASIENTOS.CODIGOASIENTO = COMPROBANTESASIENTOS.CODIGOASIENTO
	left join CABEZACOMPRAS ON (COMPROBANTESASIENTOS.TIPOCOMPROBANTE = CABEZACOMPRAS.TIPOCOMPROBANTE AND COMPROBANTESASIENTOS.NUMEROCOMPROBANTE = CABEZACOMPRAS.NUMEROCOMPROBANTE)
	WHERE CABEZAASIENTOS.FECHAMODIFICACION >= '$fecha_inicio' AND (CUERPOASIENTOS.CUENTA LIKE '422%' OR CUERPOASIENTOS.CUENTA LIKE '423%' OR CUERPOASIENTOS.CUENTA LIKE '424%') ORDER BY CABEZAASIENTOS.FECHAMODIFICACION asc;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['codigoasiento'] = strval($fb->CODIGOASIENTO);
		$data[$i]['numeroasiento'] = strval($fb->NUMEROASIENTO);
		$data[$i]['linea'] = strval($fb->LINEA);
		$data[$i]['cuenta'] = strval($fb->CUENTA);
		$data[$i]['observaciones'] = strval($fb->OBSERVACIONES);
		$data[$i]['comentarios'] = strval(@$fb->COMENTARIOS);
		$data[$i]['esdebe'] = strval($fb->ESDEBE);
		$data[$i]['porcentaje'] = strval($fb->PORCENTAJE);
		$data[$i]['monto'] = strval($fb->MONTO);
		$data[$i]['fecha'] = strval($fb->FECHA);
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$data[$i]['codigocentrocosto'] = strval($fb->CODIGOCENTROSCOSTO);
		$data[$i]['id_base'] = 1;
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function actualizar_costo_fijo($costo_fijo){
	global $db_mysql;
	$codigoasiento = trim($costo_fijo['codigoasiento']);
	$numeroasiento = trim($costo_fijo['numeroasiento']);
	$linea = intval($costo_fijo['linea']);
	$cuenta = trim($costo_fijo['cuenta']);
	$observaciones = trim($costo_fijo['observaciones']);
	$observaciones_1 = explode('/',$observaciones);
	$observaciones_2 = trim(@$observaciones_1[1]);
	$comentarios = trim(@$costo_fijo['comentarios']);
	$esdebe = trim($costo_fijo['esdebe']);
	$es_ppp = 0;
	if( validador('^ppp',strtolower($observaciones_2)) || validador('^ppp',strtolower($comentarios)) ){
		$es_ppp = 1;
	}
	$cheque_rechazado = 0;
	if( (validador('^nda',strtolower($observaciones))||validador('^ndb',strtolower($observaciones))) && !validador('banco',strtolower($observaciones)) ){
		$cheque_rechazado = 1;
	}
	$porcentaje = trim($costo_fijo['porcentaje']);
	if( $porcentaje == '' ){
		$porcentaje = 100;
	}
	$monto = trim($costo_fijo['monto']);
	if( $esdebe == 1 ){
		$total = $monto * ($porcentaje/100);
	}else{
		$total = 0 - $monto * ($porcentaje/100);
	}
	$fecha = trim($costo_fijo['fecha']);
	$array_fecha = explode(' ',$fecha);
	$fecha=$array_fecha[0];
	$array_fecha=explode('-',$fecha);
	$ano = trim($array_fecha[0]);
	$mes = trim($array_fecha[1]);
	$dia = trim($array_fecha[2]);
	$trimestre = trimestre_mes($mes);
	$fecha_mod = trim($costo_fijo['fecha_mod']);
	$codigocentrocosto = trim($costo_fijo['codigocentrocosto']);
	if( $codigocentrocosto == 1 ){
		$sucursal = 'CASA CENTRAL';
	}elseif( $codigocentrocosto == 2 ){
		$sucursal = 'MDP';
	}else{
		$sucursal ='Error';
	}
	$id_base = $costo_fijo['id_base'];

	$sql="SELECT `id_costo` FROM `costos_fijos` WHERE `codigoasiento` = '$codigoasiento' AND `linea` = '$linea' AND `id_base` = '$id_base' AND `sucursal` = '$sucursal' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$id_costo = $dat['id_costo'];
		$sql="UPDATE `costos_fijos` SET `numeroasiento` = '$numeroasiento', `cuenta` = '$cuenta', `observaciones` = '$observaciones', `comentarios` = '$comentarios', `esdebe` = '$esdebe', `es_ppp` = '$es_ppp', `cheque_rechazado` = '$cheque_rechazado', `porcentaje` = '$porcentaje', `monto` = '$monto', `total` = '$total', `fecha` = '$fecha', `ano` = '$ano', `mes` = '$mes', `dia` = '$dia', `trimestre` = '$trimestre', `fecha_mod` = '$fecha_mod', `codigocentrocosto` = '$codigocentrocosto', `sucursal` = '$sucursal' WHERE `id_costo` = '$id_costo';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `costos_fijos` SET `codigoasiento` = '$codigoasiento', `numeroasiento` = '$numeroasiento', `linea` = '$linea', `cuenta` = '$cuenta', `observaciones` = '$observaciones', `comentarios` = '$comentarios', `esdebe` = '$esdebe', `es_ppp` = '$es_ppp', `cheque_rechazado` = '$cheque_rechazado', `porcentaje` = '$porcentaje', `monto` = '$monto', `total` = '$total', `fecha` = '$fecha', `ano` = '$ano', `mes` = '$mes', `dia` = '$dia', `trimestre` = '$trimestre', `fecha_mod` = '$fecha_mod', `codigocentrocosto` = '$codigocentrocosto', `sucursal` = '$sucursal', `id_base` = '$id_base';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	echo "<p>$sql</p>";
	return false;
}


/********************************* ingresos **************************************/

function ultima_importacion_ingresos_principal(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `ingresos` WHERE `id_base` = '1' ORDER BY `fecha_mod` DESC LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2011-06-01';
	}
}

function importar_ingresos_principal(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_ingresos_principal();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "select first $limite_a_importar
	TIPOCOMPROBANTE,
	NUMEROCOMPROBANTE,
	FECHACOMPROBANTE,
	FECHAMODIFICACION,
	CODIGOCLIENTE,
	RAZONSOCIAL,
	ANULADA,
	TOTAL
	from CABEZACOMPROBANTES
	WHERE FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	AND (TIPOCOMPROBANTE = 'FA'
	OR TIPOCOMPROBANTE = 'FB'
	OR TIPOCOMPROBANTE = 'FC'
	OR TIPOCOMPROBANTE = 'NCA'
	OR TIPOCOMPROBANTE = 'NCB'
	OR TIPOCOMPROBANTE = 'NCC')
	ORDER BY FECHAMODIFICACION asc;";
	$i=0;
	$res = ibase_query($db_principal, $sql);
	while($fb = ibase_fetch_object($res)){
		$data[$i]['tipo'] = strval($fb->TIPOCOMPROBANTE);
		$data[$i]['numero'] = strval($fb->NUMEROCOMPROBANTE);
		$data[$i]['db'] = 1;
		$data[$i]['fecha'] = strval($fb->FECHACOMPROBANTE);
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$data[$i]['codigo_cliente'] = strval($fb->CODIGOCLIENTE);
		$data[$i]['razon_social'] = strval(str_replace("'","\'",mb_convert_encoding($fb->RAZONSOCIAL, "UTF-8")));
		$data[$i]['anulada'] = intval($fb->ANULADA);
		$data[$i]['total'] = floatval($fb->TOTAL);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function ultima_importacion_ingresos_ds(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `ingresos` WHERE `id_base` = '2' ORDER BY `fecha_mod` DESC LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return date('Y-m-d',strtotime($dat['fecha_mod']));
	}else{
		@mysqli_free_result($result);
		return '2011-06-01';
	}
}

function importar_ingresos_ds(){
	global $db_ds;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_ingresos_ds();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "select first $limite_a_importar
	TIPOCOMPROBANTE,
	NUMEROCOMPROBANTE,
	FECHACOMPROBANTE,
	FECHAMODIFICACION,
	CODIGOCLIENTE,
	RAZONSOCIAL,
	ANULADA,
	TOTAL
	from CABEZACOMPROBANTES
	WHERE FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	AND (TIPOCOMPROBANTE = 'FA'
	OR TIPOCOMPROBANTE = 'FB'
	OR TIPOCOMPROBANTE = 'FC'
	OR TIPOCOMPROBANTE = 'NCA'
	OR TIPOCOMPROBANTE = 'NCB'
	OR TIPOCOMPROBANTE = 'NCC')
	ORDER BY FECHAMODIFICACION asc;";
	$i=0;
	$res = ibase_query($db_ds, $sql);
	while($fb = ibase_fetch_object($res)){
		$data[$i]['tipo'] = strval($fb->TIPOCOMPROBANTE);
		$data[$i]['numero'] = strval($fb->NUMEROCOMPROBANTE);
		$data[$i]['db'] = 2;
		$data[$i]['fecha'] = strval($fb->FECHACOMPROBANTE);
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$data[$i]['codigo_cliente'] = strval($fb->CODIGOCLIENTE);
		$data[$i]['razon_social'] = strval(str_replace("'","\'",mb_convert_encoding($fb->RAZONSOCIAL, "UTF-8")));
		$data[$i]['anulada'] = intval($fb->ANULADA);
		$data[$i]['total'] = floatval($fb->TOTAL);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}


function actualizar_ingreso($ingreso){
	global $db_mysql;
	$tipo = $ingreso['tipo'];
	$numero = $ingreso['numero'];
	$db = $ingreso['db'];
	$fecha = $ingreso['fecha'];
	$fecha_mod = $ingreso['fecha_mod'];
	$codigo_cliente = $ingreso['codigo_cliente'];
	$razon_social = $ingreso['razon_social'];
	$anulada = $ingreso['anulada'];
	$total = $ingreso['total'];
	$array_fecha=explode('-',$fecha);
	$ano = trim($array_fecha[0]);
	$mes = trim($array_fecha[1]);
	$dia = trim($array_fecha[2]);
	$trimestre = trimestre_mes($mes);
	$sql="SELECT `id_ingreso` FROM `ingresos` WHERE `tipo_comprobante` = '$tipo' AND `numero_comprobante` = '$numero' AND `id_base` = '$db' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$id_ingreso = $dat['id_ingreso'];
		$sql="UPDATE `ingresos` SET `fecha` = '$fecha', `fecha_mod` = '$fecha_mod', `codigo_cliente` = '$codigo_cliente', `razon_social` = '$razon_social', `anulada` = '$anulada', `total` = '$total', `ano` = '$ano', `mes` = '$mes', `dia` = '$dia', `trimestre` = '$trimestre' WHERE `id_ingreso` = '$id_ingreso';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `ingresos` SET `tipo_comprobante` = '$tipo', `numero_comprobante` = '$numero', `id_base` = '$db', `fecha` = '$fecha', `fecha_mod` = '$fecha_mod', `codigo_cliente` = '$codigo_cliente', `razon_social` = '$razon_social', `anulada` = '$anulada', `total` = '$total', `ano` = '$ano', `mes` = '$mes', `dia` = '$dia', `trimestre` = '$trimestre';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
//	echo "<p>$sql</p>";
	return false;
}





/********************************* egresos **************************************/

function ultima_importacion_egresos_principal(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `egresos` WHERE `id_base` = '1' ORDER BY `fecha_mod` DESC LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return $dat['fecha_mod'];
	}else{
		@mysqli_free_result($result);
		return '2011-06-01 00:00:00';
		#return '2013-09-01';
	}
}

function importar_egresos_principal(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_egresos_principal();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "SELECT first $limite_a_importar
	TIPOCOMPROBANTE,
	NUMEROCOMPROBANTE,
	FECHACOMPROBANTE,
	FECHAMODIFICACION,
	CODIGOPROVEEDOR,
	RAZONSOCIAL,
	ANULADA,
	TOTAL
	FROM CABEZACOMPRAS
	WHERE (TIPOCOMPROBANTE = 'FA'
	OR TIPOCOMPROBANTE = 'FB'
	OR TIPOCOMPROBANTE = 'FC'
	OR TIPOCOMPROBANTE = 'NCA'
	OR TIPOCOMPROBANTE = 'NCB'
	OR TIPOCOMPROBANTE = 'NCC')
	AND FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	ORDER BY FECHAMODIFICACION asc;";
	$i=0;
	$res = ibase_query($db_principal, $sql);
	while($fb = ibase_fetch_object($res)){
		$data[$i]['tipo'] = strval($fb->TIPOCOMPROBANTE);
		$data[$i]['numero'] = strval($fb->NUMEROCOMPROBANTE);
		$data[$i]['db'] = 1;
		$data[$i]['fecha'] = strval($fb->FECHACOMPROBANTE);
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$data[$i]['codigo_proveedor'] = strval($fb->CODIGOPROVEEDOR);
		$data[$i]['razon_social'] = strval(str_replace("'","\'",mb_convert_encoding($fb->RAZONSOCIAL, "UTF-8")));
		$data[$i]['anulada'] = intval($fb->ANULADA);
		$data[$i]['total'] = floatval($fb->TOTAL);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
//	echo "<p>rows: $rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function ultima_importacion_egresos_ds(){
	global $db_mysql;
	$sql="SELECT `fecha_mod` FROM `egresos` WHERE `id_base` = '2' ORDER BY `fecha_mod` DESC LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return $dat['fecha_mod'];
	}else{
		@mysqli_free_result($result);
		return '2011-06-01 00:00:00';
		#return '2013-09-01';
	}
}

function importar_egresos_ds(){
	global $db_ds;
	global $limite_a_importar;
	$data = array();
	$fecha_inicio = ultima_importacion_egresos_ds();
	$fecha_inicio = formatear_fecha($fecha_inicio);
	$sql = "SELECT first $limite_a_importar
	TIPOCOMPROBANTE,
	NUMEROCOMPROBANTE,
	FECHACOMPROBANTE,
	FECHAMODIFICACION,
	CODIGOPROVEEDOR,
	RAZONSOCIAL,
	ANULADA,
	TOTAL
	FROM CABEZACOMPRAS
	WHERE (TIPOCOMPROBANTE = 'FA'
	OR TIPOCOMPROBANTE = 'FB'
	OR TIPOCOMPROBANTE = 'FC'
	OR TIPOCOMPROBANTE = 'NCA'
	OR TIPOCOMPROBANTE = 'NCB'
	OR TIPOCOMPROBANTE = 'NCC')
	AND FECHAMODIFICACION >= '$fecha_inicio 00:00:00'
	ORDER BY FECHAMODIFICACION asc;";
	$i=0;
	$res = ibase_query($db_ds, $sql);
	while($fb = ibase_fetch_object($res)){
		$data[$i]['tipo'] = strval($fb->TIPOCOMPROBANTE);
		$data[$i]['numero'] = strval($fb->NUMEROCOMPROBANTE);
		$data[$i]['db'] = 2;
		$data[$i]['fecha'] = strval($fb->FECHACOMPROBANTE);
		$data[$i]['fecha_mod'] = strval($fb->FECHAMODIFICACION);
		$data[$i]['codigo_proveedor'] = strval($fb->CODIGOPROVEEDOR);
		$data[$i]['razon_social'] = strval(str_replace("'","\'",mb_convert_encoding($fb->RAZONSOCIAL, "UTF-8")));
		$data[$i]['anulada'] = intval($fb->ANULADA);
		$data[$i]['total'] = floatval($fb->TOTAL);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
//	echo "<p>rows: $rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}


function actualizar_egreso($egreso){
	global $db_mysql;
	$tipo = $egreso['tipo'];
	$numero = $egreso['numero'];
	$db = $egreso['db'];
	$fecha = $egreso['fecha'];
	$fecha_mod = $egreso['fecha_mod'];
	$codigo_proveedor = $egreso['codigo_proveedor'];
	$razon_social = $egreso['razon_social'];
	$anulada = $egreso['anulada'];
	$total = $egreso['total'];
	$array_fecha=explode('-',$fecha);
	$ano = trim($array_fecha[0]);
	$mes = trim($array_fecha[1]);
	$dia = trim($array_fecha[2]);
	$trimestre = trimestre_mes($mes);
	$sql="SELECT `id_egreso` FROM `egresos` WHERE `tipo_comprobante` = '$tipo' AND `numero_comprobante` = '$numero' AND `id_base` = '$db' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$id_egreso = $dat['id_egreso'];
		$sql="UPDATE `egresos` SET `fecha` = '$fecha', `fecha_mod` = '$fecha_mod', `codigo_proveedor` = '$codigo_proveedor', `razon_social` = '$razon_social', `anulada` = '$anulada', `total` = '$total', `ano` = '$ano', `mes` = '$mes', `dia` = '$dia', `trimestre` = '$trimestre' WHERE `id_egreso` = '$id_egreso';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `egresos` SET `tipo_comprobante` = '$tipo', `numero_comprobante` = '$numero', `id_base` = '$db', `fecha` = '$fecha', `fecha_mod` = '$fecha_mod', `codigo_proveedor` = '$codigo_proveedor', `razon_social` = '$razon_social', `anulada` = '$anulada', `total` = '$total', `ano` = '$ano', `mes` = '$mes', `dia` = '$dia', `trimestre` = '$trimestre';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
//	echo "<p>$sql</p>";
	return false;
}

function importar_articulos_por_proveedor(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	borrar_articulos_por_proveedor();
	$sql = "select first $limite_a_importar
	ARTICULOSPROVEEDOR.CODIGOARTICULO,
	ARTICULOSPROVEEDOR.CODIGOPROVEEDOR,
	ARTICULOSPROVEEDOR.CODIGOPARTICULARPROVEEDOR,
	PROVEEDORES.RAZONSOCIAL,
	ARTICULOSPROVEEDOR.ACTIVO,
	ARTICULOSPROVEEDOR.PRECIO,
	ARTICULOSPROVEEDOR.CODIGOBONIFICACIONPROV
	from ARTICULOSPROVEEDOR
	join PROVEEDORES ON ARTICULOSPROVEEDOR.CODIGOPROVEEDOR = PROVEEDORES.CODIGOPROVEEDOR
	join ARTICULOS ON ARTICULOSPROVEEDOR.CODIGOARTICULO = ARTICULOS.CODIGOARTICULO;";
/*	$sql = "select first $limite_a_importar
	ARTICULOSPROVEEDOR.CODIGOARTICULO,
	ARTICULOSPROVEEDOR.CODIGOPROVEEDOR,
	PROVEEDORES.RAZONSOCIAL,
	ARTICULOSPROVEEDOR.ACTIVO,
	ARTICULOSPROVEEDOR.PRECIO
	from ARTICULOSPROVEEDOR
	join PROVEEDORES ON ARTICULOSPROVEEDOR.CODIGOPROVEEDOR = PROVEEDORES.CODIGOPROVEEDOR
	join ARTICULOS ON ARTICULOSPROVEEDOR.CODIGOARTICULO = ARTICULOS.CODIGOARTICULO
	WHERE ARTICULOS.ACTIVO = '1' AND PROVEEDORES.ACTIVO = '1' AND PROVEEDORES.CODIGOCLASEPROVEEDOR = '2';";*/
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['id_articulo'] = strval($fb->CODIGOARTICULO);
		$data[$i]['codigoproveedor'] = strval($fb->CODIGOPROVEEDOR);
		$data[$i]['codigoparticularproveedor'] = strval($fb->CODIGOPARTICULARPROVEEDOR);
		$data[$i]['razonsocial'] = strval(str_replace("'","\'",mb_convert_encoding($fb->RAZONSOCIAL, "UTF-8")));
		$data[$i]['activo'] = strval($fb->ACTIVO);
		$data[$i]['precio'] = floatval($fb->PRECIO);
		$data[$i]['codigo_bonificacion'] = strval($fb->CODIGOBONIFICACIONPROV);
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function actualizar_articulos_por_proveedor($articulo){
	global $db_mysql;
	$id_articulo = strval($articulo['id_articulo']);
	$codigoproveedor = strval($articulo['codigoproveedor']);
	$codigoparticularproveedor = strval($articulo['codigoparticularproveedor']);
	$razonsocial = strval($articulo['razonsocial']);
	$activo = intval($articulo['activo']);
	$precio = floatval($articulo['precio']);
	$codigo_bonificacion = strval($articulo['codigo_bonificacion']);
	$sql="SELECT `id_articulo_proveedor` FROM `articulos_proveedor` WHERE `id_articulo` = '$id_articulo' AND `codigoproveedor` = '$codigoproveedor' LIMIT 1;";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		$id_articulo_proveedor = $dat['id_articulo_proveedor'];
		$sql="UPDATE `articulos_proveedor` SET `codigoparticularproveedor` = '$codigoparticularproveedor', `razonsocial` = '$razonsocial', `activo` = '$activo', `precio` = '$precio', `codigo_bonificacion` = '$codigo_bonificacion' WHERE `id_articulo_proveedor` = '$id_articulo_proveedor' LIMIT 1;";
//		echo "<p>$sql</p>";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}else{
		@mysqli_free_result($result);
		$sql="INSERT INTO `articulos_proveedor` SET `id_articulo` = '$id_articulo', `codigoproveedor` = '$codigoproveedor', `codigoparticularproveedor` = '$codigoparticularproveedor', `razonsocial` = '$razonsocial', `activo` = '$activo', `precio` = '$precio', `codigo_bonificacion` = '$codigo_bonificacion';";
		if(mysqli_query($db_mysql,$sql)){
			return true;
		}
	}
	return false;
}

function borrar_articulos_por_proveedor(){
	global $db_mysql;
	$sql="DELETE FROM `articulos_proveedor` WHERE 1;";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function borrar_descuentos_clientes_marcas(){
	global $db_mysql;
	$sql="DELETE FROM `descuentos_clientes_marcas` WHERE 1;";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function importar_proveedores(){
	global $db_principal;
	global $limite_a_importar;
	$data = array();
	borrar_proveedores();
	$sql = "select first $limite_a_importar CODIGOPROVEEDOR, CODIGOPARTICULAR, RAZONSOCIAL, NOMBREFANTASIA from PROVEEDORES;";
	$res = ibase_query($db_principal, $sql);
	$i=0;
	while($fb = ibase_fetch_object($res)){
		$data[$i]['codigo_proveedor'] = strval(str_replace("'","\'",mb_convert_encoding($fb->CODIGOPROVEEDOR, "UTF-8")));
		$data[$i]['codigo_particular'] = strval(str_replace("'","\'",mb_convert_encoding($fb->CODIGOPARTICULAR, "UTF-8")));
		$data[$i]['razon_social'] = strval(str_replace("'","\'",mb_convert_encoding($fb->RAZONSOCIAL, "UTF-8")));
		$data[$i]['nombre_fantasia'] = strval(str_replace("'","\'",mb_convert_encoding($fb->NOMBREFANTASIA, "UTF-8")));
		$i++;
	}
	ibase_free_result( $res );
	$rows = count($data);
	//echo "<p>$rows</p>";
	if($rows>0){
		return $data;
	}elseif($rows===0){
		return '0';
	}else{
		return false;
	}
}

function actualizar_proveedor($proveedor){
	global $db_mysql;
	$codigo_proveedor = strval($proveedor['codigo_proveedor']);
	$codigo_particular = strval($proveedor['codigo_particular']);
	$razon_social = strval($proveedor['razon_social']);
	$nombre_fantasia = strval($proveedor['nombre_fantasia']);

	$sql="INSERT INTO `proveedores` SET
	`codigo_proveedor` = '$codigo_proveedor',
	`codigo_particular` = '$codigo_particular',
	`razon_social` = '$razon_social',
	`nombre_fantasia` = '$nombre_fantasia';";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
}

function borrar_proveedores(){
	global $db_mysql;
	$sql="DELETE FROM `proveedores` WHERE 1;";
	if(mysqli_query($db_mysql,$sql)){
		return true;
	}
	return false;
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

function activo_taco( $id_cliente ){
	global $db_mysql;
//	$sql="SELECT sum(`monto_total`) as `total` FROM `ventas` WHERE UNIX_TIMESTAMP(`fecha`) > (UNIX_TIMESTAMP(NOW())-7862400) AND `id_cliente` = '$id_cliente' AND `anulada` = '0';";
	//3 meses hacia atrás desde ahora
	$sql="SELECT sum(`monto_total`) as `total` FROM `ventas` 
	WHERE `ano` = YEAR(NOW()) AND `mes` = MONTH(NOW()) 
	AND `id_cliente` = '$id_cliente' AND `anulada` = '0';";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		if( $dat['total'] >= get_config('monto_cliente_activo') ){
			return 1;
		}
	}
	return 0;
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

function get_query_ids($ids_asociados){
	$condicion_ids = [];
	foreach($ids_asociados as $id){
		$condicion_ids[] = "`id_cliente` = '" . $id . "'";
	}
	$query_ids = implode(' OR ', $condicion_ids);
	return '(' . $query_ids . ')';
}

function cliente_activo_con_excel( $id_cliente ){
	global $db_mysql;
	$ids_asociados = ids_clientes_asociados( $id_cliente );
	$get_query_ids = get_query_ids($ids_asociados);

	$sql="SELECT sum(`monto_total`) as `total` FROM `ventas` 
	WHERE `ano` = YEAR(NOW()) AND `mes` = MONTH(NOW()) 
	AND $get_query_ids AND `anulada` = '0';";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		if( $dat['total'] >= get_config('monto_cliente_activo') ){
			return 1;
		}
	}
	return 0;
}

function es_cliente_excel( $codigo_particular ){
	global $db_mysql;
	$sql="SELECT `id_asociacion` 
	FROM `asociaciones_clientes` WHERE 
	`cuenta_op_distrisuper` = '$codigo_particular' OR
	`cuenta_op_fric_rot` = '$codigo_particular'
	LIMIT 1;";
	//echo "<p>$sql</p>";
	$result=mysqli_query($db_mysql,$sql);
	if($dat=@mysqli_fetch_array($result)){
		@mysqli_free_result($result);
		return 1;
	}
	return 0;
}

function ultima_importacion_entregas($id_base, $db){
	$sql="SELECT `fecha_mod_cabeza` FROM `entregas` WHERE `id_base` = '$id_base' ORDER BY `fecha_mod_cabeza` desc LIMIT 1;";
	$result=mysqli_query($db, $sql);
	$dat=@mysqli_fetch_array($result);
	if( !isset($dat['fecha_mod_cabeza']) ){
		return '2021-06-01 00:00:00';
	}
	$fecha_mod_cabeza = $dat['fecha_mod_cabeza'];
	@mysqli_free_result($result);

	$sql="SELECT `fecha_mod_cuerpo` FROM `entregas` WHERE `id_base` = '$id_base' ORDER BY `fecha_mod_cuerpo` desc LIMIT 1;";
	$result=mysqli_query($db, $sql);
	$dat=@mysqli_fetch_array($result);
	if( !isset($dat['fecha_mod_cuerpo']) ){
		return '2021-06-01 00:00:00';
	}
	$fecha_mod_cuerpo = $dat['fecha_mod_cuerpo'];
	@mysqli_free_result($result);

	if( strtotime($fecha_mod_cabeza) < strtotime($fecha_mod_cuerpo) ){
		return $fecha_mod_cabeza;
	}else{
		return $fecha_mod_cuerpo;
	}
}

function borrar_entregas( $fecha_inicio, $id_base, $db ){
	$sql="DELETE FROM `entregas` WHERE `id_base` = '$id_base' AND
	(
		`fecha_mod_cabeza` >= '$fecha_inicio' OR
		`fecha_mod_cuerpo` >= '$fecha_inicio'
	);";
    //echo $sql;
	if(mysqli_query($db,$sql)){
		return true;
	}
	return false;
}

function borrar_entrega( $comprobante, $fecha_inicio, $db ){
	$sql="DELETE FROM `entregas` WHERE
	`tipo_comprobante` = '{$comprobante['tipo_comprobante']}' AND
	`numero_comprobante` = '{$comprobante['numero_comprobante']}' AND
	`codigo_proveedor` = '{$comprobante['codigo_proveedor']}' AND
	`linea` = '{$comprobante['linea']}' AND
	`id_base` = '{$comprobante['id_base']}';";
    //echo $sql;
	if(mysqli_query($db,$sql)){
		return true;
	}
	return false;
}

function importar_entregas( $limite_a_importar, $id_base, $db, $db_flexxus ){
	$data = array();
	$fecha_inicio = ultima_importacion_entregas($id_base, $db);
	if( borrar_entregas( $fecha_inicio, $id_base, $db ) ){
		$sql = "SELECT first $limite_a_importar
		CABEZA.TIPOCOMPROBANTE,
		CABEZA.NUMEROCOMPROBANTE,
		CABEZA.FECHACOMPROBANTE,
		CABEZA.RAZONSOCIAL,
		CABEZA.ANULADA,
		CABEZA.FECHAMODIFICACION as FECHAMODCABEZA,
		CUERPO.FECHAMODIFICACION as FECHAMODCUERPO,
		CUERPO.LINEA,
		CUERPO.CODIGOARTICULO,
		CUERPO.CODIGOPROVEEDOR,
		CUERPO.DESCRIPCION,
		CUERPO.CANTIDAD,
		CUERPO.DESCUENTO,
		CUERPO.PRECIOUNITARIO,
		CUERPO.PRECIOTOTAL,
		CUERPO.CANTIDADREMITIDA,
		CUERPO.CODIGODEPOSITO,
		CUERPO.PORCENTAJEIVA,
		CUERPO.IMPUESTOSINTERNOS,
		CUERPO.CANTIDADINGRESADA,
		CUERPO.PRECIOINGRESADO
		FROM CABEZACOMPRAS as CABEZA
		JOIN CUERPOCOMPRAS as CUERPO ON 
			(
				CUERPO.NUMEROCOMPROBANTE = CABEZA.NUMEROCOMPROBANTE AND 
				CUERPO.TIPOCOMPROBANTE = CABEZA.TIPOCOMPROBANTE AND 
				CUERPO.CODIGOPROVEEDOR = CABEZA.CODIGOPROVEEDOR
			)
		WHERE 
			(
				CABEZA.TIPOCOMPROBANTE = 'FA' OR 
				CABEZA.TIPOCOMPROBANTE = 'FB' OR 
				CABEZA.TIPOCOMPROBANTE = 'NCA' OR 
				CABEZA.TIPOCOMPROBANTE = 'NCB' OR 
				CABEZA.TIPOCOMPROBANTE = 'RI' OR 
				CABEZA.TIPOCOMPROBANTE = 'RE'
			)
		AND (
			CABEZA.FECHAMODIFICACION >= '$fecha_inicio' OR
			CUERPO.FECHAMODIFICACION >= '$fecha_inicio'
		)
		ORDER BY FECHAMODCUERPO asc;";
		$res = ibase_query($db_flexxus, $sql);
		$i=0;
		while($fb = ibase_fetch_object($res)){
			$data[$i]['tipo_comprobante'] = strval($fb->TIPOCOMPROBANTE);
			$data[$i]['numero_comprobante'] = strval($fb->NUMEROCOMPROBANTE);
			$data[$i]['fecha_comprobante'] = strval($fb->FECHACOMPROBANTE);
			$data[$i]['razon_social'] = str_replace("'","\'",mb_convert_encoding($fb->RAZONSOCIAL, "UTF-8"));
			$data[$i]['anulada'] = intval($fb->ANULADA);
			$data[$i]['fecha_mod_cabeza'] = strval($fb->FECHAMODCABEZA);
			$data[$i]['fecha_mod_cuerpo'] = strval($fb->FECHAMODCUERPO);
			$data[$i]['linea'] = intval($fb->LINEA);
			$data[$i]['id_articulo'] = strval($fb->CODIGOARTICULO);
			$data[$i]['codigo_proveedor'] = strval($fb->CODIGOPROVEEDOR);
			$data[$i]['descripcion'] = str_replace("'","\'",mb_convert_encoding($fb->DESCRIPCION, "UTF-8"));
			$data[$i]['cantidad'] = intval($fb->CANTIDAD);
			$data[$i]['descuento'] = floatval($fb->DESCUENTO);
			$data[$i]['precio_unitario'] = intval($fb->PRECIOUNITARIO);
			$data[$i]['precio_total'] = floatval($fb->PRECIOTOTAL);
			$data[$i]['cantidad_remitida'] = intval($fb->CANTIDADREMITIDA);
			$data[$i]['codigo_deposito'] = intval($fb->CODIGODEPOSITO);
			$data[$i]['porcentaje_iva'] = floatval($fb->PORCENTAJEIVA);
			$data[$i]['impuestos_internos'] = floatval($fb->IMPUESTOSINTERNOS);
			$data[$i]['cantidad_ingresada'] = intval($fb->CANTIDADINGRESADA);
			$data[$i]['precio_ingresado'] = floatval($fb->PRECIOINGRESADO);
			$data[$i]['id_base'] = intval($id_base);

			// parche para borrar comprobantes que ya no estén en la base de flexxus y fueron reemplazados con otro que tiene el mismo código
			borrar_entrega( $data[$i], $fecha_inicio, $db );

			$i++;
		}
		ibase_free_result( $res );
		$rows = count($data);
		// echo "<p>$rows</p>";
		if($rows>0){
			return $data;
		}elseif($rows===0){
			echo "<p>$sql</p>";
			return '0';
		}else{
			return false;
		}
	}
	return false;
}


function actualizar_entrega($comprobante, $db){
	$sql="INSERT INTO `entregas` SET
	`tipo_comprobante` = '{$comprobante['tipo_comprobante']}',
	`numero_comprobante` = '{$comprobante['numero_comprobante']}',
	`fecha_comprobante` = '{$comprobante['fecha_comprobante']}',
	`razon_social` = '{$comprobante['razon_social']}',
	`anulada` = '{$comprobante['anulada']}',
	`fecha_mod_cabeza` = '{$comprobante['fecha_mod_cabeza']}',
	`fecha_mod_cuerpo` = '{$comprobante['fecha_mod_cuerpo']}',
	`linea` = '{$comprobante['linea']}',
	`id_articulo` = '{$comprobante['id_articulo']}',
	`codigo_proveedor` = '{$comprobante['codigo_proveedor']}',
	`descripcion` = '{$comprobante['descripcion']}',
	`cantidad` = '{$comprobante['cantidad']}',
	`descuento` = '{$comprobante['descuento']}',
	`precio_unitario` = '{$comprobante['precio_unitario']}',
	`precio_total` = '{$comprobante['precio_total']}',
	`cantidad_remitida` = '{$comprobante['cantidad_remitida']}',
	`codigo_deposito` = '{$comprobante['codigo_deposito']}',
	`porcentaje_iva` = '{$comprobante['porcentaje_iva']}',
	`impuestos_internos` = '{$comprobante['impuestos_internos']}',
	`cantidad_ingresada` = '{$comprobante['cantidad_ingresada']}',
	`precio_ingresado` = '{$comprobante['precio_ingresado']}',
	`id_base` = '{$comprobante['id_base']}';";
//	echo "<p>$sql</p>";
	if(mysqli_query($db,$sql)){
		return true;
	}
	echo "<p>$sql</p>";
	return false;
}

function get_plan_de_cuentas(){
	global $db_mysql;
	$plan_de_cuentas = [];
	$sql="SELECT * FROM `plan de cuentas` LIMIT 9999;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$plan_de_cuentas[] = $dat;
	}
	return $plan_de_cuentas;
}

function get_regla_general_esp(){
	global $db_mysql;
	$regla_general_esp = [];
	$sql="SELECT * FROM `regla_general_esp` ORDER BY CHAR_LENGTH(`cuenta`) ASC LIMIT 9999;";
	$result=mysqli_query($db_mysql,$sql);
	while($dat=@mysqli_fetch_array($result)){
		$regla_general_esp[] = $dat;
	}
	return $regla_general_esp;
}

//$sql="OPTIMIZE TABLE `deudas_proveedores`;";
//mysqli_query($db_mysql,$sql);

function mensajePanelAlarmas($tabla, $jsonData, $result){

	// $jsonData = {
	// 	"StartTime": "acá iría la hora de inicio",
	// 	"EndTime": "acá iría la hora de finalizacion"
	// }

	$url = "https://api.distriapi.com.ar/api/panel-alarmas";

	$data = array(
		"name" => $tabla,
		"url" => "/actualizador",
		"caller" => "Taco",
		"result" => $result,
		"resultDetail" => $jsonData,
		"type" => "auto",
	);

	$ch = curl_init($url);

	// Configurar opciones de cURL
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	$response = curl_exec($ch);

	// Verificar si hubo algún error en la petición
	// if (curl_errno($ch)) {
	// 	echo "Error: " . curl_error($ch);
	// }

	// Cerrar la sesión de cURL
	curl_close($ch);
	// Manejar la respuesta de la petición
	// echo $response;
}
