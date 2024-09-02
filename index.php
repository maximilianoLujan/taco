<?php
ini_set("session.cookie_lifetime","172800");
ini_set("session.gc_maxlifetime","172800");
session_start();

/******* Configuracion ******/
ini_set('display_errors', 1);
date_default_timezone_set('America/Argentina/Buenos_Aires');
mb_internal_encoding("UTF-8");
mb_http_output("UTF-8");
mb_regex_encoding("UTF-8");
mb_detect_order("UTF-8");
ob_start("mb_output_handler");

$path = './roles.txt';
$roles = array();
$errors = array();
$mensajes = array();

if( file_exists($path) ){
	$file = fopen($path,"r");
	while( !feof($file) ){
		$roles[] = trim(fgets($file));
	}
	fclose($file);
}

if( in_array( trim($_SESSION['rol']), $roles ) && strlen( $_SESSION['rol'] ) > 4 ){
	include_once('conexion-mysql.php');
	include_once('funciones-base-de-datos.php');
	include_once('funciones.php');
}else{
	$_SESSION['volver'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login/');
}

?><!DOCTYPE html>
<html lang="es-ES">
<head>
<meta charset="UTF-8" />
<title>Distrisuper</title>
<meta name="viewport" content="initial-scale=1.0, width=device-width" />
<link rel="stylesheet" href="./general.css?" type="text/css" />
<link rel="stylesheet" href="./est.css?" type="text/css" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.5.0/css/font-awesome.min.css">
<script src="./js/jquery-1.7.2.min.js"></script>
<script src="./script.js?"></script>
<?php
$ultima_importacion = strtotime(ultima_importacion());
$hora_actual = time();
$dias_desde_ultima_actualizacion = floor( ($hora_actual - $ultima_importacion) / 86400);
if( $dias_desde_ultima_actualizacion > 2 ){
?>
<script>
alert('Hace <?php echo $dias_desde_ultima_actualizacion;?> dias que no se actualiza la base de datos');
</script>
<?php } ?>
<link rel="shortcut icon" href="/favicon.ico" />
</head>
<body>
<div id="barra-fija">
	<a href="/logout/" class="boton" style="float:right;height:25px;">Cerrar Sesion</a>
	<div id="menu-principal">
		<form method="post" id="formulario-principal">
			<a href="./" id="logo-taco"></a>
			<input type="button" name="vta" id="vta" value="VTA" />
			<input type="button" name="actualizar" id="actualizar" value="Actualizar" />
			<input type="button" name="ajuste-inflacion" id="ajuste-inflacion" value="A.I No" />
			<input type="button" name="ver" id="ver" value="Ver $" />
			<input type="button" name="sucursal" id="sucursal" value="Total" />
			<input type="button" name="boton-unidad-tiempo-inicio" id="boton-unidad-tiempo-inicio" value="Meses" />
			<input type="button" name="boton-scripts" id="boton-scripts" value="Scripts" />
			<input type="button" name="phpmyadmin" id="phpmyadmin" value="MyAdmin" />
			<input type="button" name="boton-pass" id="boton-pass" value="Cambiar Contraseña" />
			<div class="clear"></div>
		</form>
		<div class="clear"></div>
	</div>
	<div id="clientes-activos">C.A <?php echo clientes_activos(); ?></div>
	<?php
		if(alarma_deuda(50000, 100)){
	?>
		<div id="alert-info-2"><a href="./alarma-cobranza/"><i class="fa fa-exclamation-circle"></i></a></div>
	<?php } ?>

	<?php
		$sql = "SELECT * FROM `variables` WHERE `k` = 'errores-flexxus' AND `v` != '0' LIMIT 1;";
		$result=mysqli_query($db_mysql,$sql);
		if($dat=mysqli_fetch_array($result)){
	?>
		<div id="alert-info"><a href="./scripts/errores-flexxus.html?"><i class="fa fa-exclamation-circle"></i></a></div>
	<?php } ?>
</div>
<div id="wrapper">
	<div id="datos-principal"></div>

	<div style="padding:0.5em 1em; margin: 0.5em 1em 0; background:#ddddff;display:inline-block;">
		<h3 style="margin:0; padding:0 0 0 0.6em;">Informes PBI</h3>
		<div class="bloque_grafico">
			<a href="https://app.powerbi.com/groups/a5cdfdd9-b905-4abb-b566-46b43396f778/reports/ddd56130-8b1e-4640-8654-56fb0adf58db/ReportSection5e67034852810e4f7c38" target="_blank" class="titulo">DEUDORES</a>
		</div>
		<div class="bloque_grafico">
			<a href="https://app.powerbi.com/groups/a5cdfdd9-b905-4abb-b566-46b43396f778/reports/ddd56130-8b1e-4640-8654-56fb0adf58db/ReportSection68ea1a3ee922ffa3a3f6" target="_blank" class="titulo">ACREEDORES</a>
		</div>
		<div class="bloque_grafico">
			<a href="https://app.powerbi.com/groups/a5cdfdd9-b905-4abb-b566-46b43396f778/reports/ddd56130-8b1e-4640-8654-56fb0adf58db/ReportSectiond25fcce41102bb653a74" target="_blank" class="titulo">CAJA</a>
		</div>

		<div class="bloque_grafico">
			<a href="https://app.powerbi.com/groups/a5cdfdd9-b905-4abb-b566-46b43396f778/reports/ddd56130-8b1e-4640-8654-56fb0adf58db/ReportSection3419dccab94f720de370" target="_blank" class="titulo">EERR (Promedio)</a>
		</div>
		<div class="bloque_grafico">
			<a href="https://app.powerbi.com/groups/a5cdfdd9-b905-4abb-b566-46b43396f778/reports/85696941-e7f4-464a-8bdb-dced36a5d6a4/ReportSection05257b945e4a83dd3a88" target="_blank" class="titulo">STOCK $</a>
		</div>
		<div class="bloque_grafico">
			<a href="https://app.powerbi.com/groups/a5cdfdd9-b905-4abb-b566-46b43396f778/reports/85696941-e7f4-464a-8bdb-dced36a5d6a4/ReportSection6033658f0688c1c7950e" target="_blank" class="titulo">STOCK U</a>
		</div>
		<div class="clear"></div>
	</div>
	<div class="clear"></div>

	<div style="padding:0.5em 1em; margin: 0 1em;">
		<div class="bloque_grafico">
			<a href="./ventas" class="titulo">VENTAS</a>
			<?php if(@$_GET['mostrar']==1){ ?>
			<a href="./ventas"><img src="./ventas.php" class="grafico-dinamico" /></a>
			<?php } ?>
		</div>
		<div class="bloque_grafico">
			<a href="./rentabilidad" class="titulo">RENTABILIDAD</a>
			<?php if(@$_GET['mostrar']==1){ ?>
			<a href="./rentabilidad"><img src="./rentabilidad.php" class="grafico-dinamico" /></a>
			<?php } ?>
		</div>
		<div class="bloque_grafico">
			<a href="./stock" class="titulo">STOCK</a>
			<?php if(@$_GET['mostrar']==1){ ?>
			<a href="./stock"><img src="./stock.php" class="grafico-dinamico" /></a>
			<?php } ?>
		</div>
		<div class="bloque_grafico">
			<a href="./deudas-clientes" class="titulo">DEUDAS CLIENTES</a>
			<?php if(@$_GET['mostrar']==1){ ?>
			<a href="./deudas-clientes"><img src="./deudas_clientes.php" class="grafico-dinamico" /></a>
			<?php } ?>
		</div>
		<div class="bloque_grafico">
			<a href="./deudas-proveedores" class="titulo">DEUDAS PROVEEDORES</a>
			<?php if(@$_GET['mostrar']==1){ ?>
			<a href="./deudas-proveedores"><img src="./deudas_proveedores.php" class="grafico-dinamico" /></a>
			<?php } ?>
		</div>
		<div class="bloque_grafico">
			<a href="./cobranzas" class="titulo">COBRANZAS</a>
			<?php if(@$_GET['mostrar']==1){ ?>
			<a href="./cobranzas"><img src="./cobranzas.php" class="grafico-dinamico" /></a>
			<?php } ?>
		</div>
		<div class="bloque_grafico">
			<a href="./ingresos-egresos" class="titulo">INGRESOS / EGRESOS</a>
			<?php if(@$_GET['mostrar']==1){ ?>
			<a href="./ingresos-egresos"><img src="./ingresos-egresos.php" class="grafico-dinamico" /></a>
			<?php } ?>
		</div>
		<div class="bloque_grafico">
			<a href="./caja" class="titulo">CAJA</a>
			<?php if(@$_GET['mostrar']==1){ ?>
			<a href="./caja"><img src="./caja.php" class="grafico-dinamico" /></a>
			<?php } ?>
		</div>
		<div class="bloque_grafico">
			<a href="./disponibilidad" class="titulo">DISPONIBILIDAD</a>
			<?php if(@$_GET['mostrar']==1){ ?>
			<a href="./disponibilidad"><img src="./disponibilidad.php" class="grafico-dinamico" /></a>
			<?php } ?>
		</div>

		<div class="bloque_grafico">
			<a href="./ventas-cliente" class="titulo">VENTAS CLIENTE</a>
		</div>
		<div class="bloque_grafico">
			<a href="./ventas-por-marcas" class="titulo">VENTAS MARCA</a>
		</div>
		<div class="bloque_grafico">
			<a href="./ventas-super-rubro" class="titulo">VENTAS SUPER RUBRO</a>
		</div>
		<div class="bloque_grafico">
			<a href="./ventas-ciudad-mes" class="titulo">VENTAS CIUDAD MES</a>
		</div>
		<div class="bloque_grafico">
			<a href="./ventas-ciudad-ano" class="titulo">VENTAS CIUDAD AÑO</a>
		</div>
		<div class="bloque_grafico">
			<a href="./inflacion" class="titulo">INFLACION</a>
		</div>
		<div class="bloque_grafico">
			<a href="./ppp" class="titulo">INFORME PPP</a>
		</div>
		<div class="clear"></div>
	</div>
</div>
</body>
</html>
