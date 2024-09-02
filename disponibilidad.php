<?php
if( @$_GET ){
	ini_set('display_errors', 0);
	require('conexion-mysql.php');
	require('funciones-base-de-datos.php');
	require('funciones.php');

	/* pChart library inclusions */
	include("class/pData.class.php");
	include("class/pDraw.class.php");
	include("class/pImage.class.php");

	$ver = $_GET['ver'];
	$sucursal = $_GET['sucursal'];
	$ai = $_GET['ai'];
	$periodo = $_GET['periodo'];

//	$fechas = array_reverse(fechas_a_mostrar($periodo,0,12));
	$fechas = fechas_a_mostrar($periodo,0,12);

	$cacheKey = urlencode('disponibilidad-'.$sucursal.'-'.$ai.'-'.$periodo.'-'.implode('-',$fechas));// no necesitamos "ver" siempre va en pesos
	$ChartHash = md5($cacheKey);
	if(file_exists("./cache/".$ChartHash.".png")){
		$im = imagecreatefrompng("./cache/".$ChartHash.".png");
		header('Content-Type: image/png');
		imagepng($im);
		imagedestroy($im);
	}else{
		foreach($fechas as $fecha){
			$array_fechas_legibles[] = fecha_img($fecha);
			$valores[$fecha] = disponibilidad($fecha,$periodo);
		}
		if($ai == 'A.I Si'){
			$ajuste_inflacion = array();
			foreach($fechas as $fecha){
				$ajuste_inflacion[$fecha] = 1;
			}
			$ajuste_inflacion = ajuste_inflacion($fechas,$periodo);
			$inflacion_acumulada = 1;
//			$valores = array_reverse( $valores, true );
			foreach($valores as $fecha=>$v){
				if( is_numeric($ajuste_inflacion[$fecha]) ){
					$inflacion_acumulada = $inflacion_acumulada * $ajuste_inflacion[$fecha];
					$valores_i[$fecha] = $v * $inflacion_acumulada;
				}
			}
//			$valores = array_reverse( $valores_i, true );
			$valores = $valores_i;
		}
		$colores_por_promedio = colores_por_promedio($valores,$fechas,$periodo,1);//el uno es para el ajuste en el ultimo periodo
		// Dataset definition
		$myData = new pData();
		$myData->addPoints($valores,"ventas");
		$myData->addPoints($array_fechas_legibles,"Labels");
		$myData->setAbscissa("Labels");
		// Initialise the graph
		$myPicture = new pImage(344,223,$myData);
		$myPicture->setGraphArea(10,10,338,197);
		$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/MankSans.ttf","FontSize"=>12));
		$Settings = array("AxisAlpha"=>50, "LabelSkip"=>1, "Mode"=>SCALE_MODE_START0);
		$myPicture->drawScale($Settings);
		$Config = array("DisplayValues"=>1, "AroundZero"=> 1, "colores_por_promedio"=>$colores_por_promedio, "Interleave"=>0, "Surrounding"=>-100, "DisplayPos"=>LABEL_POS_INSIDE);
		$myPicture->drawBarChart($Config);
		$myPicture->render("./cache/".$ChartHash.".png");
		$myPicture->stroke();
	}
}












?>
