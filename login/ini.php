<?php
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
require('funciones.php');
?>
