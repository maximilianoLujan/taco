<?php
$time_start = microtime(true);
set_time_limit(900);
//date_default_timezone_set('America/Argentina/Buenos_Aires'); // va mas abajo, para obtener la hora del servidor antes de ver si actualiza o no
ini_set('memory_limit', '512M');
ini_set('display_errors', 1);
require( __DIR__ . '/../conexion-firebird.php');
require( __DIR__ . '/../conexion-mysql.php');
require( __DIR__ . '/funciones.php');
$limite_a_importar = 25000;

$correctos = $errores = 0;
$err = array();

$actualizar = 0;

$time = intval(date('H'));

if( $time >= 3 ){
	$actualizar = 1;
}else{
	$err[] = date('Y-m-d H:i:s');
}

if( $actualizar == 1 || @$_GET['forzar']==1 ){ // parche para evitar actualizaciones por las noches
	date_default_timezone_set('America/Argentina/Buenos_Aires');
	$articulos= get_articulos();
	$clientes= get_clientes();
	$super_rubros= get_super_rubros();
	$rubros= get_rubros();
	$marcas= get_marcas();

	$startTime = date();
	$ventas_principal = importar_ventas_principal();

	if(is_array($ventas_principal)){
		$descuento_proveedor = importar_descuentos_proveedor();

		foreach($ventas_principal as $venta){
			if(array_key_exists( strval($venta['id_cliente']), $clientes) ){
				$venta['id_ciudad'] = $clientes[ strval($venta['id_cliente']) ]['id_ciudad'];
			}else{
				$venta['id_ciudad'] = 'Error';
			}
			if(array_key_exists( strval($venta['id_cliente']), $clientes) ){
				$venta['id_provincia'] = $clientes[ strval($venta['id_cliente']) ]['id_provincia'];
			}else{
				$venta['id_provincia'] = 'Error';
			}
			if(array_key_exists( strval($venta['id_cliente']), $clientes) ){
				$venta['id_zona'] = $clientes[ strval($venta['id_cliente']) ]['id_zona'];
			}else{
				$venta['id_zona'] = 'Error';
			}
			if(array_key_exists( strval($venta['id_cliente']), $clientes) ){
				$venta['id_actividad'] = $clientes[ strval($venta['id_cliente']) ]['id_actividad'];
			}else{
				$venta['id_actividad'] = 'Error';
			}
			if(array_key_exists( strval($venta['id_articulo']), $articulos) ){
				$venta['id_marca'] = $articulos[ strval($venta['id_articulo']) ]['id_marca'];
			}else{
				$venta['id_marca'] = 'Error';
			}
			if(array_key_exists( strval($venta['id_articulo']), $articulos) ){
				$venta['id_rubro'] = $articulos[ strval($venta['id_articulo']) ]['id_rubro'];
			}else{
				$venta['id_rubro'] = 'Error';
			}
			if(array_key_exists( strval($venta['id_rubro']), $rubros) ){
				$venta['id_super_rubro'] = $rubros[ strval($venta['id_rubro']) ]['id_super_rubro'];
			}else{
				$venta['id_super_rubro'] = 'Error';
			}
			if(array_key_exists( strval($venta['id_super_rubro']), $super_rubros) ){
				$venta['id_grupo_super_rubro'] = $super_rubros[ strval($venta['id_super_rubro']) ]['id_grupo_super_rubro'];
			}else{
				$venta['id_grupo_super_rubro'] = 'Error';
			}

			if( array_key_exists(strval($venta['id_articulo']),$articulos) && isset($descuento_proveedor[strval(@$articulos['codigo_proveedor'])]) ){
				$venta['costo_venta'] = floatval($venta['costo_venta']) * floatval($descuento_proveedor[strval($articulos['codigo_proveedor'])]);
			}

			$venta['id_base'] = 1;
			$numeropuntoventa = intval(trim($venta['numero_comprobante']));
			if(
				(($numeropuntoventa >= 1700000000 && $numeropuntoventa < 1900000000) || ($numeropuntoventa >= 170000000 && $numeropuntoventa < 190000000)) &&
				substr( strval($numeropuntoventa), 0, 6) != '189999'
				){
				$venta['id_tipo_venta'] = 2;
				if(array_key_exists( strval($venta['id_cliente']), $clientes) ){
					$venta['porcentaje_descuento_gm'] = $clientes[ strval($venta['id_cliente']) ]['porcentaje_descuento_gm'];
				}else{
					$venta['porcentaje_descuento_gm'] = 0;
				}
			}else{
				$venta['id_tipo_venta'] = 1;
				$venta['porcentaje_descuento_gm'] = 0;
			}
			if(array_key_exists( strval($venta['id_marca']), $marcas) ){//parche SOLMI
				if($marcas[ strval($venta['id_marca']) ] == 'SOLMI  #  CONTRAPESOS DE BALANCEO Y SUPLEMENTOS #'){//parche SOLMI
					$venta['cantidad']= ($venta['cantidad'] / 50);//parche SOLMI
					$venta['valor_unidad']= ($venta['valor_unidad'] * 50);//parche SOLMI
				}
			}
			$array_fecha = explode(' ',trim($venta['fecha']));
			$fecha=$array_fecha[0];
			$array_fecha=explode('-',$fecha);
			$venta['ano']=$array_fecha[0];
			$venta['mes']=$array_fecha[1];
			$venta['dia']=$array_fecha[2];
			if(actualizar_venta($venta)){
				$correctos++;
			}else{
				$err[] = $venta;
				$errores++;
			}
		}
		
		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('Ventas Principal', $jsonData, 1);
	}else{
		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('Ventas Principal', $jsonData, 0);
	}

	//////////////////////////////////////////////////////////////////////

	$startTime = date();
	$ventas_ds = importar_ventas_ds();

	if(is_array($ventas_ds)){
		foreach($ventas_ds as $venta){
			if(array_key_exists( strval($venta['id_cliente']), $clientes) ){
				$venta['id_ciudad'] = $clientes[ strval($venta['id_cliente']) ]['id_ciudad'];
			}else{
				$venta['id_ciudad'] = 'Error';
			}
			if(array_key_exists( strval($venta['id_cliente']), $clientes) ){
				$venta['id_provincia'] = $clientes[ strval($venta['id_cliente']) ]['id_provincia'];
			}else{
				$venta['id_provincia'] = 'Error';
			}
			if(array_key_exists( strval($venta['id_cliente']), $clientes) ){
				$venta['id_zona'] = $clientes[ strval($venta['id_cliente']) ]['id_zona'];
			}else{
				$venta['id_zona'] = 'Error';
			}
			if(array_key_exists( strval($venta['id_cliente']), $clientes) ){
				$venta['id_actividad'] = $clientes[ strval($venta['id_cliente']) ]['id_actividad'];
			}else{
				$venta['id_actividad'] = 'Error';
			}
			if(array_key_exists( strval($venta['id_articulo']), $articulos) ){
				$venta['id_marca'] = $articulos[ strval($venta['id_articulo']) ]['id_marca'];
			}else{
				$venta['id_marca'] = 'Error';
			}
			if(array_key_exists( strval($venta['id_articulo']), $articulos) ){
				$venta['id_rubro'] = $articulos[ strval($venta['id_articulo']) ]['id_rubro'];
			}else{
				$venta['id_rubro'] = 'Error';
			}
			if(array_key_exists( strval($venta['id_rubro']), $rubros) ){
				$venta['id_super_rubro'] = $rubros[ strval($venta['id_rubro']) ]['id_super_rubro'];
			}else{
				$venta['id_super_rubro'] = 'Error';
			}
			if(array_key_exists( strval($venta['id_super_rubro']), $super_rubros) ){
				$venta['id_grupo_super_rubro'] = $super_rubros[ strval($venta['id_super_rubro']) ]['id_grupo_super_rubro'];
			}else{
				$venta['id_grupo_super_rubro'] = 'Error';
			}
			$venta['id_base'] = 2;
			$venta['id_tipo_venta'] = 1;
			$venta['porcentaje_descuento_gm'] = 0;
			if(array_key_exists( strval($venta['id_marca']), $marcas) ){//parche SOLMI
				if($marcas[ strval($venta['id_marca']) ] == 'SOLMI  #  CONTRAPESOS DE BALANCEO Y SUPLEMENTOS #'){//parche SOLMI
					$venta['cantidad']= ($venta['cantidad'] / 50);//parche SOLMI
					$venta['valor_unidad']= ($venta['valor_unidad'] * 50);//parche SOLMI
				}
			}
			$array_fecha = explode(' ',trim($venta['fecha']));
			$fecha=$array_fecha[0];
			$array_fecha=explode('-',$fecha);
			$venta['ano']=$array_fecha[0];
			$venta['mes']=$array_fecha[1];
			$venta['dia']=$array_fecha[2];
			if(actualizar_venta($venta)){
				$correctos++;
			}else{
				$err[] = $venta;
				$errores++;
			}
		}

		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('Ventas DS', $jsonData, 1);
	}else{
		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('Ventas DS', $jsonData, 0);
	}

	//////////////////////////////////////////////////////////////////////

	$startTime = date();
	$comprobantes = importar_cabezas_comprobantes(1);// principal
	if(is_array($comprobantes)){
		foreach($comprobantes as $comprobante){
			if(actualizar_cabeza_comprobante($comprobante)){
				$correctos++;
			}else{
				$err[] = $comprobante;
				$errores++;
			}
		}

		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('Cabezas comprobantes Principal', $jsonData, 1);
	}else{
		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('Cabezas comprobantes Principal', $jsonData, 0);
	}
	
	$startTime = date();
	$comprobantes = importar_cuerpos_comprobantes(1);// principal
	if(is_array($comprobantes)){
		foreach($comprobantes as $comprobante){
			if(actualizar_cuerpo_comprobante($comprobante)){
				$correctos++;
			}else{
				$err[] = $comprobante;
				$errores++;
			}
		}
	
		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('Cuerpos comprobantes Principal', $jsonData, 1);
	}else{
		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('Cuerpos comprobantes Principal', $jsonData, 0);
	}


	///////////////////////////////////////////////////////////////////////


	$startTime = date();
	$comprobantes = importar_cabezas_comprobantes(2);// DS
	if(is_array($comprobantes)){
		foreach($comprobantes as $comprobante){
			if(actualizar_cabeza_comprobante($comprobante)){
				$correctos++;
			}else{
				$err[] = $comprobante;
				$errores++;
			}
		}

		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('Cabezas comprobantes DS', $jsonData, 1);
	}else{
		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('Cabezas comprobantes DS', $jsonData, 0);
	}


	$startTime = date();
	$comprobantes = importar_cuerpos_comprobantes(2);// DS
	if(is_array($comprobantes)){
		foreach($comprobantes as $comprobante){
			if(actualizar_cuerpo_comprobante($comprobante)){
				$correctos++;
			}else{
				$err[] = $comprobante;
				$errores++;
			}
		}
	
		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('Cuerpos comprobantes DS', $jsonData, 1);
	}else{
		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('Cuerpos comprobantes DS', $jsonData, 0);
	}


	////////////////////////////////////////////////////////////////////////7


	$startTime = date();
	$asociaciones = importar_asociaciones_clientes();
	if(is_array($asociaciones)){
		foreach($asociaciones as $asociacion){
			if(actualizar_asociacion_cliente($asociacion)){
				$correctos++;
			}else{
				$err[] = $asociacion;
				$errores++;
			}
		}

		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('Asociaciones clientes', $jsonData, 1);
	}else{
		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('Asociaciones clientes', $jsonData, 0);
	}


	//////////////////////////////////////////////////////////////////////////7


	$startTime = date();
	$cae_afip = importar_cae_afip();
	if(is_array($cae_afip)){
		foreach($cae_afip as $cae){
			if(actualizar_cae_afip($cae)){
				$correctos++;
			}else{
				$err[] = $comprobante;
				$errores++;
			}
		}
	
		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('CAE AFIP', $jsonData, 1);
	}else{
		$jsonData = json_encode([
			"StartTime" => $startTime,
			"EndTime" => date()
		]);
		mensajePanelAlarmas('CAE AFIP', $jsonData, 0);
	}

} // fin parche para evitar actualizaciones por las noches


?><!DOCTYPE html>
<html lang="es-ES">
<head>
<meta charset="UTF-8" />
<title>Distrisuper</title>
<meta name="viewport" content="initial-scale=1.0, width=device-width" />
<link rel="stylesheet" href="./est.css" type="text/css" />
<link rel="shortcut icon" href="/favicon.ico" />
</head>
<body>
<br /><br />
<div id="estado-importacion">
<a href="./">volver</a>
<p>Importados correctamente: <span><?php echo $correctos;?></span></p>
<p>Errores: <span><?php echo $errores;?></span></p>
</div>


<?php
if(count($err)){
	echo "\n<pre>";
	print_r($err);
	echo "</pre>\n";
}



include_once( __DIR__ . '/../scripts/stock-en-transito.php' );



$time_end = microtime(true);
$time = $time_end - $time_start;
#echo "----------------------------------------------------------------------------------------------\n<br />";
#echo "\n" . '<div style="padding:3px 12px;">' . $time . "segundos.</div><br /><br /><br />\n";
print("\n<div style='position:absolute;left:0;top:0;padding:3px 12px;'> $time segundos. RAM: ".memory_get_usage()/1024 . " Kb") . "</div>\n";
?>


<?php if($correctos == $limite_a_importar){ ?>
<script>
setTimeout(location.reload(),10*1000);
</script>
<?php } ?>
</body>
</html>







