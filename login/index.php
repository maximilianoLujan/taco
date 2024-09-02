<?php
ini_set("session.cookie_lifetime","172800");
ini_set("session.gc_maxlifetime","172800");
session_start();

/******* Configuracion ******/
ini_set('display_errors', 0);
date_default_timezone_set('America/Argentina/Buenos_Aires');
mb_internal_encoding("UTF-8");
mb_http_output("UTF-8");
mb_regex_encoding("UTF-8");
mb_detect_order("UTF-8");
ob_start("mb_output_handler");

/******** Coneccion *********/
require('../conexion-mysql.php');
/********* Funciones ********/

$errors = array();
$mensajes = array();

if( !@$_SESSION['volver'] ){
	$_SESSION['volver'] = $_SERVER['HTTP_HOST'];
}

if( @$_POST['accion']=='login' ){
	$u = $_POST['user'];
	$p = md5($_POST['pass']);
	$sql="SELECT `rol` FROM `usuarios` WHERE `usuario` = '$u' AND `password` = '$p' LIMIT 1;";
//	echo '<p>sql-->' . $sql . '</p>';
	$result=mysqli_query($db_mysql,$sql);
	if($dat=mysqli_fetch_array($result)){
		$_SESSION['rol'] = $dat['rol'];
		$_SESSION['user'] = $u;
//		echo '<p>1-->' . $_SESSION['rol'] . '</p>';
		header('Location: http://' . $_SESSION['volver']);
///		echo '<p>2-->' . $_SESSION['volver'] . '</p>';
	}else{
		$errors[]='Usuario o contraseña erroneos.';
		unset($_SESSION['rol']);
		unset($_SESSION['user']);
	}
}

?><!DOCTYPE html>
<html lang="es-ES">
<head>
	<title>Login</title>
	<meta http-equiv="content-type" content="text/html;charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, width=device-width" />
	<link rel="stylesheet" href="../general.css?" type="text/css" />
	<script src="../js/jquery-1.7.2.min.js"></script>
	<script src="./script.js?"></script>
	<link rel="shortcut icon" href="/favicon.ico" />
	<style>
		body{
		vertical-align:top;
		text-align:center;
		}
		#principal{
		margin:90px auto;
		}
		#principal .recuadro{
		display:inline-block;
		margin:0 auto;
		padding:6px 40px 20px;
		background:#ddd;
		-webkit-border-radius: 14px;
		-moz-border-radius: 14px;
		border-radius: 14px;
		}
		#principal .recuadro label{
		display:inline-block;
		width:160px;
		text-align:right;
		padding: 0 6px 0 0;
		}
		.errores{
		display:inline-block;
		margin:10px auto;
		color:#f00;
		border:solid 1px #f00;
		padding:6px 20px;
		background:#ff0;
		}
		.mensajes{
		display:inline-block;
		margin:10px auto;
		color:#070;
		border:solid 1px #000;
		padding:6px 20px;
		background:#ff0;
		}
		#boton-home{
		position: fixed;
		top:3px;
		left:3px;
		z-index:99;
		font-size:1.2em;
		font-weight:bolder;
		color:#fff !important;
		display:inline-block;
		border:0 none;
		padding:2px 6px 1px;
		margin:6px 6px;
		overflow:hidden;
		background-color: #0000cc;
		background-image:-webkit-gradient(linear, left top, left bottom, color-stop(0, #5555ff), color-stop(30px, #0000cc)); /* Saf4+, Chrome */
		background-image:-webkit-linear-gradient(top, #5555ff 0, #0000cc 30px); /* Chrome 10+, Saf5.1+, iOS 5+ */
		background-image:-moz-linear-gradient(top, #5555ff 0, #0000cc 30px); /* FF3.6 */
		background-image:-ms-linear-gradient(top, #5555ff 0, #0000cc 30px); /* IE10 */
		background-image:-o-linear-gradient(top, #5555ff 0, #0000cc 30px); /* Opera 11.10+ */
		background-image:linear-gradient(top, #5555ff 0, #0000cc 30px);
		-webkit-border-radius: 4px;
		-moz-border-radius: 4px;
		border-radius: 4px;
		cursor:pointer;
		}
	</style>
</head>
<body>
<?php
	if(@$_SESSION['rol']){
		echo '<a href="/logout/" class="boton" style="float:right;">Cerrar Sesion</a><br />';
	}
?>
<div id="principal">
	<?php
		if(count($errors)){
			echo '<h3 class="errores">' . implode("<br />\n",$errors) . '</h3><br />';
		}
		if(count($mensajes)){
			echo '<h3 class="mensajes">' . implode("<br />\n",$mensajes) . '</h3><br />';
		}
	?>
	<div class="recuadro">
		<h3>Login</h3>
		<form method="post">
			<input type="hidden" name="accion" value="login" />
			<label for="user">Usuario:</label><input type="text" name="user" id="user" value="<?php echo @$_POST['user'];?>" /><br />
			<label for="pass">Contraseña:</label><input type="password" name="pass" id="pass" /><br />
			<input type="submit" name="enviar" value="Entrar" /><br />
		</form>
	</div>
</div>
</body>
</html>











