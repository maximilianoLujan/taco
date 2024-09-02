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
	require('../conexion-mysql.php');
	require('../conexion-dimes.php');
	require('funciones.php');
}else{
	$_SESSION['volver'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	header('Location: http://' . $_SERVER['HTTP_HOST'] . '/login/');
}

?>
